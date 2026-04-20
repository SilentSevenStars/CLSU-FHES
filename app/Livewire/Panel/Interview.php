<?php

namespace App\Livewire\Panel;

use App\Models\College;
use App\Models\Evaluation;
use App\Models\Interview as ModelsInterview;
use App\Models\PanelAssignment;
use App\Services\AccountActivityService;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Interview extends Component
{
    public $evaluationId;
    public $evaluation;
    public $applicant;
    public $position;
    public $jobApplication;

    public $general_appearance       = 0;
    public $manner_of_speaking       = 0;
    public $physical_conditions      = 0;
    public $alertness                = 0;
    public $self_confidence          = 0;
    public $ability_to_present_ideas = 0;
    public $maturity_of_judgement    = 0;

    public $currentPage        = 1;
    public $totalScore         = 0;
    public $showApplicantModal = false;

    private const EXPERIENCE_ONLY_COLLEGES = [
        'College of Veterinary Science and Medicine',
        'College of Business Administration and Accountancy',
        'College of Engineering',
    ];

    private const EXPERIENCE_ONLY_POSITIONS = [
        'instructor iii',
        'assistant professor i',
    ];

    /**
     * Resolve the effective college name for the job application.
     *
     * Priority:
     * 1. If position has a college_id → use position's college name.
     * 2. If position college_id is null (various/university-wide) → fall back
     *    to the applicant's college_id and look up that college's name.
     */
    private function resolveCollegeName(): string
    {
        $jobApp    = $this->evaluation->jobApplication;
        $position  = $jobApp->position;
        $applicant = $jobApp->applicant;

        // Position has a specific college assigned — use it
        if (!is_null($position?->college_id)) {
            return $position->college?->name ?? '';
        }

        // Position college is null (various) → use applicant's college
        if (!is_null($applicant?->college_id)) {
            return College::find($applicant->college_id)?->name ?? '';
        }

        return '';
    }

    protected function isExperienceOnlyHead(): bool
    {
        $panelPosition     = strtolower(Auth::user()->panel?->panel_position ?? '');
        $applicantPosition = strtolower($this->evaluation->jobApplication->position->name ?? '');
        $collegeName       = $this->resolveCollegeName();

        return $panelPosition === 'head'
            && in_array($applicantPosition, self::EXPERIENCE_ONLY_POSITIONS)
            && in_array($collegeName, self::EXPERIENCE_ONLY_COLLEGES);
    }

    public function updatingCurrentPage($value)
    {
        if ($this->currentPage == 1) {
            $this->persistPage1Scores();
        } elseif ($this->currentPage == 2) {
            $this->persistPage2Scores();
        }
    }

    protected function persistPage1Scores(): void
    {
        $user = Auth::user();

        $panelAssignment = PanelAssignment::where('user_id', $user->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();

        if (!$panelAssignment) return;

        if (!$panelAssignment->interview_id) {
            $interview = ModelsInterview::create([
                'general_appearance'       => $this->general_appearance,
                'manner_of_speaking'       => $this->manner_of_speaking,
                'physical_conditions'      => $this->physical_conditions,
                'alertness'                => $this->alertness,
                'self_confidence'          => 0,
                'ability_to_present_ideas' => 0,
                'maturity_of_judgement'    => 0,
                'total_score'              => 0,
            ]);

            $panelAssignment->update(['interview_id' => $interview->id]);
            return;
        }

        ModelsInterview::where('id', $panelAssignment->interview_id)->update([
            'general_appearance'  => $this->general_appearance,
            'manner_of_speaking'  => $this->manner_of_speaking,
            'physical_conditions' => $this->physical_conditions,
            'alertness'           => $this->alertness,
        ]);
    }

    protected function persistPage2Scores(): void
    {
        $user = Auth::user();

        $panelAssignment = PanelAssignment::where('user_id', $user->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();

        if (!$panelAssignment) return;

        if (!$panelAssignment->interview_id) {
            $interview = ModelsInterview::create([
                'general_appearance'       => 0,
                'manner_of_speaking'       => 0,
                'physical_conditions'      => 0,
                'alertness'                => 0,
                'self_confidence'          => $this->self_confidence,
                'ability_to_present_ideas' => $this->ability_to_present_ideas,
                'maturity_of_judgement'    => $this->maturity_of_judgement,
                'total_score'              => 0,
            ]);

            $panelAssignment->update(['interview_id' => $interview->id]);
            return;
        }

        ModelsInterview::where('id', $panelAssignment->interview_id)->update([
            'self_confidence'          => $this->self_confidence,
            'ability_to_present_ideas' => $this->ability_to_present_ideas,
            'maturity_of_judgement'    => $this->maturity_of_judgement,
        ]);
    }

    public function mount($evaluationId, $page = null)
    {
        $this->evaluationId = $evaluationId;

        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.applicant',   // college_id is a plain column, no relation needed
            'jobApplication.position.college',
        ])->findOrFail($evaluationId);

        $this->jobApplication = $this->evaluation->jobApplication;
        $this->applicant      = $this->jobApplication->applicant;
        $this->position       = $this->jobApplication->position;

        if (session()->has('returnPage')) {
            $this->currentPage = (int) session()->pull('returnPage');
        } elseif ($page !== null) {
            $this->currentPage = (int) $page;
        }

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

        if ($panelAssignment->interview_id) {
            $interview = ModelsInterview::find($panelAssignment->interview_id);
            if ($interview) {
                $this->general_appearance       = (int) $interview->general_appearance;
                $this->manner_of_speaking       = (int) $interview->manner_of_speaking;
                $this->physical_conditions      = (int) $interview->physical_conditions;
                $this->alertness                = (int) $interview->alertness;
                $this->self_confidence          = (int) $interview->self_confidence;
                $this->ability_to_present_ideas = (int) $interview->ability_to_present_ideas;
                $this->maturity_of_judgement    = (int) $interview->maturity_of_judgement;
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

    public function calculateTotal()
    {
        $this->totalScore = $this->general_appearance
            + $this->manner_of_speaking
            + $this->physical_conditions
            + $this->alertness
            + $this->self_confidence
            + $this->ability_to_present_ideas
            + $this->maturity_of_judgement;
    }

    public function nextPage()
    {
        if ($this->currentPage == 1) {
            $this->validate([
                'general_appearance'  => 'required|integer|min:1|max:5',
                'manner_of_speaking'  => 'required|integer|min:1|max:5',
                'physical_conditions' => 'required|integer|min:1|max:5',
                'alertness'           => 'required|integer|min:1|max:5',
            ], [
                'general_appearance.required'  => 'Please rate General Appearance',
                'manner_of_speaking.required'  => 'Please rate Manner of Speaking',
                'physical_conditions.required' => 'Please rate Physical Conditioning',
                'alertness.required'           => 'Please rate Alertness',
            ]);

            $this->persistPage1Scores();
        }

        $this->currentPage++;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function confirmSubmission()
    {
        $this->validate([
            'general_appearance'       => 'required|integer|min:1|max:5',
            'manner_of_speaking'       => 'required|integer|min:1|max:5',
            'physical_conditions'      => 'required|integer|min:1|max:5',
            'alertness'                => 'required|integer|min:1|max:5',
            'self_confidence'          => 'required|integer|min:1|max:5',
            'ability_to_present_ideas' => 'required|integer|min:1|max:5',
            'maturity_of_judgement'    => 'required|integer|min:1|max:5',
        ], [
            'self_confidence.required'          => 'Please rate Self Confidence',
            'ability_to_present_ideas.required' => 'Please rate Ability to Present Ideas',
            'maturity_of_judgement.required'    => 'Please rate Maturity of Judgement',
        ]);

        $applicantPosition = strtolower($this->evaluation->jobApplication->position->name ?? '');
        $panelPosition     = strtolower(Auth::user()->panel?->panel_position ?? '');
        $isInstructorIorII = in_array($applicantPosition, ['instructor i', 'instructor ii']);

        if ($panelPosition === 'head' && ($isInstructorIorII || $this->isExperienceOnlyHead())) {
            $this->saveInterview();
        } else {
            $this->dispatch('show-swal-confirm');
        }
    }

    public function saveInterview()
    {
        $this->calculateTotal();

        $user            = Auth::user();
        $panelAssignment = null;
        $interview       = null;

        $panelAssignment = PanelAssignment::where('user_id', $user->id)
            ->where('evaluation_id', $this->evaluationId)
            ->first();

        $data = [
            'general_appearance'       => $this->general_appearance,
            'manner_of_speaking'       => $this->manner_of_speaking,
            'physical_conditions'      => $this->physical_conditions,
            'alertness'                => $this->alertness,
            'self_confidence'          => $this->self_confidence,
            'ability_to_present_ideas' => $this->ability_to_present_ideas,
            'maturity_of_judgement'    => $this->maturity_of_judgement,
            'total_score'              => $this->totalScore,
        ];

        if ($panelAssignment && $panelAssignment->interview_id) {
            ModelsInterview::where('id', $panelAssignment->interview_id)->update($data);
            $interview = ModelsInterview::find($panelAssignment->interview_id);
        } else {
            $interview = ModelsInterview::create($data);
            if ($panelAssignment) {
                $panelAssignment->update(['interview_id' => $interview->id]);
            }
        }

        $applicantPosition = strtolower($this->evaluation->jobApplication->position->name ?? '');
        $panelPosition     = strtolower(Auth::user()->panel?->panel_position ?? '');
        $isInstructorIorII = in_array($applicantPosition, ['instructor i', 'instructor ii']);

        $applicantName = trim(
            $this->applicant->first_name . ' '
            . ($this->applicant->middle_name ? $this->applicant->middle_name . ' ' : '')
            . $this->applicant->last_name
        );

        if ($this->isExperienceOnlyHead()) {
            AccountActivityService::log(
                Auth::user(),
                "Completed interview evaluation for applicant \"{$applicantName}\" "
                    . "(Evaluation ID: {$this->evaluationId})."
            );

            return redirect()->route('panel.experience', [
                'evaluationId' => $this->evaluationId,
            ]);

        } elseif ($panelPosition === 'head' && $isInstructorIorII) {
            AccountActivityService::log(
                Auth::user(),
                "Completed interview evaluation for applicant \"{$applicantName}\" "
                    . "(Evaluation ID: {$this->evaluationId})."
            );

            return redirect()->route('panel.experience', [
                'evaluationId' => $this->evaluationId,
            ]);

        } elseif ($isInstructorIorII) {
            AccountActivityService::log(
                Auth::user(),
                "Completed interview evaluation for applicant \"{$applicantName}\" "
                    . "(Evaluation ID: {$this->evaluationId})."
            );

            return redirect()->route('panel.performance', [
                'evaluationId' => $this->evaluationId,
                'interviewId'  => $interview->id,
            ]);

        } else {
            if ($panelAssignment) {
                $panelAssignment->update(['status' => 'complete']);
            }

            AccountActivityService::log(
                Auth::user(),
                "Completed interview evaluation for applicant \"{$applicantName}\" "
                    . "(Evaluation ID: {$this->evaluationId})."
            );

            $this->dispatch('interview-saved');
        }
    }

    public function render()
    {
        $panel = Auth::user()->panel;

        return view('livewire.panel.interview', [
            'panel' => $panel,
        ]);
    }
}