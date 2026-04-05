<?php

namespace App\Livewire\Panel;

use App\Models\Evaluation;
use App\Models\Performance as ModelsPerformance;
use App\Models\PersonalCompetence;
use App\Models\Skill;
use App\Models\PanelAssignment;
use App\Services\AccountActivityService;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Performance extends Component
{
    public $evaluationId;
    public $interviewId;
    public $evaluation;
    public $applicant;
    public $position;
    public $jobApplication;

    public $question1        = '';
    public $question2        = '';
    public $question3        = '';
    public $question4        = '';
    public $question5        = '';
    public $personalQuestion1 = '';

    public $currentPage        = 1;
    public $totalScore         = 0;
    public $showApplicantModal = false;

    public function updatingCurrentPage($value)
    {
        if ($this->currentPage == 1) {
            $this->persistPage1Scores();
        } elseif ($this->currentPage == 2) {
            $this->persistPage2Scores();
        }
    }

    protected $enumValues = [
        'VS' => 5,
        'S'  => 4,
        'F'  => 3,
        'P'  => 2,
        'NI' => 1,
    ];

    public function mount($evaluationId, $interviewId)
    {
        $this->evaluationId = $evaluationId;
        $this->interviewId  = $interviewId;

        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])->findOrFail($evaluationId);

        $this->jobApplication = $this->evaluation->jobApplication;
        $this->applicant      = $this->jobApplication->applicant;
        $this->position       = $this->jobApplication->position;

        $user = Auth::user();

        $panelAssignment = PanelAssignment::firstOrCreate(
            [
                'user_id'       => $user->id,
                'evaluation_id' => $evaluationId,
            ],
            [
                'status' => 'not yet',
            ]
        );

        if ($panelAssignment->performance_id) {
            $performance = ModelsPerformance::find($panelAssignment->performance_id);
            if ($performance) {
                $skill = Skill::find($performance->skill_id);
                if ($skill) {
                    $this->question1 = $skill->question1;
                    $this->question2 = $skill->question2;
                    $this->question3 = $skill->question3;
                    $this->question4 = $skill->question4;
                    $this->question5 = $skill->question5;
                }

                $personalCompetence = PersonalCompetence::find($performance->personal_competence_id);
                if ($personalCompetence) {
                    $this->personalQuestion1 = $personalCompetence->question1;
                }
            }
        }
    }

    public function toggleApplicantModal()
    {
        $this->showApplicantModal = !$this->showApplicantModal;
    }

    public function getFileDataUrl()
    {
        $encryptionService = new FileEncryptionService();

        if (
            !$this->jobApplication->requirements_file ||
            !$encryptionService->fileExists($this->jobApplication->requirements_file)
        ) {
            $this->dispatch('show-error', message: 'File not found.');
            return null;
        }

        try {
            $decryptedContents = $encryptionService->decryptFile($this->jobApplication->requirements_file);
            return 'data:application/pdf;base64,' . base64_encode($decryptedContents);
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Error loading file: ' . $e->getMessage());
            return null;
        }
    }

    public function calculateSkillTotal(): int
    {
        return ($this->enumValues[$this->question1] ?? 0)
             + ($this->enumValues[$this->question2] ?? 0)
             + ($this->enumValues[$this->question3] ?? 0)
             + ($this->enumValues[$this->question4] ?? 0)
             + ($this->enumValues[$this->question5] ?? 0);
    }

    public function calculatePersonalTotal(): int
    {
        return $this->enumValues[$this->personalQuestion1] ?? 0;
    }

    protected function persistPage1Scores(): void
    {
        if (!$this->question1 || !$this->question2 || !$this->question3
            || !$this->question4 || !$this->question5) {
            return;
        }

        $user = Auth::user();

        $panelAssignment = PanelAssignment::where('user_id', $user->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();
        if (!$panelAssignment) return;

        $skillData = [
            'question1' => $this->question1,
            'question2' => $this->question2,
            'question3' => $this->question3,
            'question4' => $this->question4,
            'question5' => $this->question5,
        ];

        if ($panelAssignment->performance_id) {
            $performance = ModelsPerformance::find($panelAssignment->performance_id);
            Skill::where('id', $performance->skill_id)->update($skillData);
        } else {
            $skill              = Skill::create($skillData);
            $personalCompetence = PersonalCompetence::create(['question1' => 'NI']);
            $performance        = ModelsPerformance::create([
                'skill_id'               => $skill->id,
                'personal_competence_id' => $personalCompetence->id,
                'total_score'            => 0,
            ]);
            $panelAssignment->update(['performance_id' => $performance->id]);
        }
    }

    protected function persistPage2Scores(): void
    {
        if (!$this->personalQuestion1) return;

        $user = Auth::user();

        $panelAssignment = PanelAssignment::where('user_id', $user->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();
        if (!$panelAssignment || !$panelAssignment->performance_id) return;

        $performance = ModelsPerformance::find($panelAssignment->performance_id);
        PersonalCompetence::where('id', $performance->personal_competence_id)
            ->update(['question1' => $this->personalQuestion1]);
    }

    public function nextPage()
    {
        if ($this->currentPage == 1) {
            $this->validate([
                'question1' => 'required|in:VS,S,F,P,NI',
                'question2' => 'required|in:VS,S,F,P,NI',
                'question3' => 'required|in:VS,S,F,P,NI',
                'question4' => 'required|in:VS,S,F,P,NI',
                'question5' => 'required|in:VS,S,F,P,NI',
            ], [
                'question1.required' => 'Please rate knowledgeability and mastery',
                'question2.required' => 'Please rate way of communication',
                'question3.required' => 'Please rate skill in initiating',
                'question4.required' => 'Please rate way of motivating',
                'question5.required' => 'Please rate skill in sustaining',
            ]);

            $this->persistPage1Scores();
        }

        $this->currentPage++;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->persistPage2Scores();
            $this->currentPage--;
        }
    }

    public function returnToPrevious()
    {
        $this->persistPage1Scores();

        $panelPosition = strtolower(Auth::user()->panel?->panel_position ?? '');

        if ($panelPosition === 'head') {
            return redirect()->route('panel.experience', [
                'evaluationId' => $this->evaluationId,
            ]);
        }

        return redirect()
            ->route('panel.interview', ['evaluationId' => $this->evaluationId])
            ->with('returnPage', 2);
    }

    public function confirmSubmission()
    {
        $this->validate([
            'question1'         => 'required|in:VS,S,F,P,NI',
            'question2'         => 'required|in:VS,S,F,P,NI',
            'question3'         => 'required|in:VS,S,F,P,NI',
            'question4'         => 'required|in:VS,S,F,P,NI',
            'question5'         => 'required|in:VS,S,F,P,NI',
            'personalQuestion1' => 'required|in:VS,S,F,P,NI',
        ], [
            'personalQuestion1.required' => 'Please rate composed behavior while teaching',
        ]);

        $this->dispatch('show-swal-confirm');
    }

    public function savePerformance()
    {
        $user = Auth::user();

        $panelAssignment = PanelAssignment::where('user_id', $user->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();

        if ($panelAssignment && $panelAssignment->performance_id) {
            $performance = ModelsPerformance::find($panelAssignment->performance_id);

            Skill::where('id', $performance->skill_id)->update([
                'question1' => $this->question1,
                'question2' => $this->question2,
                'question3' => $this->question3,
                'question4' => $this->question4,
                'question5' => $this->question5,
            ]);

            PersonalCompetence::where('id', $performance->personal_competence_id)->update([
                'question1' => $this->personalQuestion1,
            ]);

            $performance->update([
                'total_score' => $this->calculateSkillTotal() + $this->calculatePersonalTotal(),
            ]);
        } else {
            $skill = Skill::create([
                'question1' => $this->question1,
                'question2' => $this->question2,
                'question3' => $this->question3,
                'question4' => $this->question4,
                'question5' => $this->question5,
            ]);

            $personalCompetence = PersonalCompetence::create([
                'question1' => $this->personalQuestion1,
            ]);

            $performance = ModelsPerformance::create([
                'skill_id'               => $skill->id,
                'personal_competence_id' => $personalCompetence->id,
                'total_score'            => $this->calculateSkillTotal() + $this->calculatePersonalTotal(),
            ]);

            if ($panelAssignment) {
                $panelAssignment->update(['performance_id' => $performance->id]);
            }
        }

        if ($panelAssignment) {
            $panelAssignment->update(['status' => 'complete']);
        }

        $applicantName = trim(
            $this->applicant->first_name . ' '
            . ($this->applicant->middle_name ? $this->applicant->middle_name . ' ' : '')
            . $this->applicant->last_name
        );

        AccountActivityService::log(
            Auth::user(),
            "Completed performance evaluation for applicant \"{$applicantName}\" "
                . "(Evaluation ID: {$this->evaluationId})."
        );

        $this->dispatch('performance-saved');
    }

    public function render()
    {
        return view('livewire.panel.performance');
    }
}