<?php

namespace App\Livewire\Panel;

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
        $user  = Auth::user();
        $panel = $user->panel;

        if (!$panel) return;

        $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
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
        $user  = Auth::user();
        $panel = $user->panel;

        if (!$panel) return;

        $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
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
            'jobApplication.position',
        ])->findOrFail($evaluationId);

        $this->jobApplication = $this->evaluation->jobApplication;
        $this->applicant      = $this->jobApplication->applicant;
        $this->position       = $this->jobApplication->position;

        if (session()->has('returnPage')) {
            $this->currentPage = (int) session()->pull('returnPage');
        } elseif ($page !== null) {
            $this->currentPage = (int) $page;
        }

        $user  = Auth::user();
        $panel = $user->panel;

        if ($panel) {
            $panelAssignment = PanelAssignment::firstOrCreate(
                [
                    'panel_id'      => $panel->id,
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

        // head + Instructor I/II → redirect directly to Experience (no confirm dialog)
        if ($panelPosition === 'head' && $isInstructorIorII) {
            $this->saveInterview();
        } else {
            // non-head + Instructor I/II → confirm then go to Performance
            // any + other position → confirm then mark complete
            $this->dispatch('show-swal-confirm');
        }
    }

    public function saveInterview()
    {
        $this->calculateTotal();

        $user  = Auth::user();
        $panel = $user->panel;

        $panelAssignment = null;
        $interview       = null;

        if ($panel) {
            $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
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
        }

        $applicantPosition = strtolower($this->evaluation->jobApplication->position->name ?? '');
        $panelPosition     = strtolower($panel?->panel_position ?? '');
        $isInstructorIorII = in_array($applicantPosition, ['instructor i', 'instructor ii']);

        $applicantName = trim(
            $this->applicant->first_name . ' '
            . ($this->applicant->middle_name ? $this->applicant->middle_name . ' ' : '')
            . $this->applicant->last_name
        );

        if ($panelPosition === 'head' && $isInstructorIorII) {
            // head + Instructor I/II → Interview → Experience → Performance
            AccountActivityService::log(
                Auth::user(),
                "Completed interview evaluation for applicant \"{$applicantName}\" "
                    . "(Evaluation ID: {$this->evaluationId})."
            );

            return redirect()->route('panel.experience', [
                'evaluationId' => $this->evaluationId,
            ]);

        } elseif ($isInstructorIorII) {
            // non-head + Instructor I/II → Interview → Performance
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
            // any + other position → Interview only, mark complete
            if ($panel && $panelAssignment) {
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
        return view('livewire.panel.interview');
    }
}