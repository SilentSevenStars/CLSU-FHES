<?php

namespace App\Livewire\Panel;

use App\Models\Evaluation;
use App\Models\Performance as ModelsPerformance;
use App\Models\PersonalCompetence;
use App\Models\Skill;
use App\Models\PanelAssignment;
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

    public $question1 = '';
    public $question2 = '';
    public $question3 = '';
    public $question4 = '';
    public $question5 = '';

    public $personalQuestion1 = '';

    public $currentPage  = 1;
    public $totalScore   = 0;
    public $showApplicantModal = false;

    /**
     * Called automatically by Livewire before currentPage is updated.
     * This ensures we persist any unsaved data from the current page
     * before navigating to a different page.
     */
    public function updatingCurrentPage($value)
    {
        // Only persist data from the CURRENT page, not all pages
        // This prevents page 2 values from overwriting page 1 values when going back
        if ($this->currentPage == 1) {
            // On page 1: persist only skill questions (question1-5)
            $this->persistPage1Scores();
        } elseif ($this->currentPage == 2) {
            // On page 2: persist only personal competence question
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

        $user  = Auth::user();
        $panel = $user->panel;

        if ($panel) {
            // Use firstOrCreate so we NEVER reset status/data on re-mount
            $panelAssignment = PanelAssignment::firstOrCreate(
                [
                    'panel_id'      => $panel->id,
                    'evaluation_id' => $evaluationId,
                ],
                [
                    'status' => 'not yet',
                ]
            );

            // Load existing performance data back into component properties
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

    /**
     * Persist ONLY page-1 skill fields (question1–5).
     * Never called while on page 2, so personalQuestion1 can never
     * corrupt the saved skill answers.
     */
    protected function persistPage1Scores(): void
    {
        if (!$this->question1 || !$this->question2 || !$this->question3
            || !$this->question4 || !$this->question5) {
            return;
        }

        $user  = Auth::user();
        $panel = $user->panel;
        if (!$panel) return;

        $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
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
            // Only update the skill columns — never touch personal_competence here
            Skill::where('id', $performance->skill_id)->update($skillData);
        } else {
            // Create records for the first time; personalQuestion1 hasn’t been set yet,
            // so store the lowest enum value instead of null to satisfy the
            // NOT NULL constraint. It will be overwritten on page‑2 if necessary.
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

    /**
     * Persist ONLY page-2 personal competence field (personalQuestion1).
     * Never called while on page 1, so the skill answers are never touched here.
     */
    protected function persistPage2Scores(): void
    {
        if (!$this->personalQuestion1) return;

        $user  = Auth::user();
        $panel = $user->panel;
        if (!$panel) return;

        $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();
        if (!$panelAssignment || !$panelAssignment->performance_id) return;

        $performance = ModelsPerformance::find($panelAssignment->performance_id);
        // Only update the personal_competence column — never touch skill columns here
        PersonalCompetence::where('id', $performance->personal_competence_id)
            ->update(['question1' => $this->personalQuestion1]);
    }

    /**
     * Persist whatever scores are currently in memory to the DB.
     * Called on every page navigation (next / previous / return to interview)
     * so that switching pages never loses entered values.
     */
    protected function persistPerformanceScores(): void
    {
        // Only persist skill questions — personalQuestion1 may not be filled yet
        if (!$this->question1 || !$this->question2 || !$this->question3
            || !$this->question4 || !$this->question5) {
            return;
        }

        $user  = Auth::user();
        $panel = $user->panel;

        if (!$panel) {
            return;
        }

        $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();

        if (!$panelAssignment) {
            return;
        }

        if ($panelAssignment->performance_id) {
            // Update existing records
            $performance = ModelsPerformance::find($panelAssignment->performance_id);

            Skill::where('id', $performance->skill_id)->update([
                'question1' => $this->question1,
                'question2' => $this->question2,
                'question3' => $this->question3,
                'question4' => $this->question4,
                'question5' => $this->question5,
            ]);

            if ($this->personalQuestion1) {
                PersonalCompetence::where('id', $performance->personal_competence_id)->update([
                    'question1' => $this->personalQuestion1,
                ]);

                $performance->update([
                    'total_score' => $this->calculateSkillTotal() + $this->calculatePersonalTotal(),
                ]);
            }
        } else {
            // Only create full records once both sections have data
            if (!$this->personalQuestion1) {
                // Create with skill data only; personal competence added later
                $skill = Skill::create([
                    'question1' => $this->question1,
                    'question2' => $this->question2,
                    'question3' => $this->question3,
                    'question4' => $this->question4,
                    'question5' => $this->question5,
                ]);

                $personalCompetence = PersonalCompetence::create(['question1' => 'NI']);

                $performance = ModelsPerformance::create([
                    'skill_id'               => $skill->id,
                    'personal_competence_id' => $personalCompetence->id,
                    'total_score'            => 0,
                ]);

                $panelAssignment->update(['performance_id' => $performance->id]);
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

                $panelAssignment->update(['performance_id' => $performance->id]);
            }
        }
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

            // Persist page-1 scores before advancing so they are safe in DB
            $this->persistPage1Scores();
        }

        $this->currentPage++;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            // updatingCurrentPage will handle persistence
            $this->currentPage--;
        }
    }

    public function returnToInterview()
    {
        // Save page-specific data before redirecting
        if ($this->currentPage == 1) {
            $this->persistPage1Scores();
        } else {
            $this->persistPage2Scores();
        }

        // Redirect back to interview page 2 (the last page of the interview form)
        return redirect()
            ->route('panel.interview', ['evaluationId' => $this->evaluationId])
            ->with('returnPage', 2);
    }

    public function confirmSubmission()
    {
        $this->validate([
            'question1'       => 'required|in:VS,S,F,P,NI',
            'question2'       => 'required|in:VS,S,F,P,NI',
            'question3'       => 'required|in:VS,S,F,P,NI',
            'question4'       => 'required|in:VS,S,F,P,NI',
            'question5'       => 'required|in:VS,S,F,P,NI',
            'personalQuestion1' => 'required|in:VS,S,F,P,NI',
        ], [
            'personalQuestion1.required' => 'Please rate composed behavior while teaching',
        ]);

        $this->dispatch('show-swal-confirm');
    }

    public function savePerformance()
    {
        $user  = Auth::user();
        $panel = $user->panel;

        if ($panel) {
            $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
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
        }

        $this->dispatch('performance-saved');
    }

    public function render()
    {
        return view('livewire.panel.performance');
    }
}