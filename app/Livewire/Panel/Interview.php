<?php

namespace App\Livewire\Panel;

use App\Models\Evaluation;
use App\Models\Interview as ModelsInterview;
use App\Models\PanelAssignment;
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

    public $general_appearance    = 0;
    public $manner_of_speaking    = 0;
    public $physical_conditions   = 0;
    public $alertness             = 0;
    public $self_confidence       = 0;
    public $ability_to_present_ideas = 0;
    public $maturity_of_judgement = 0;

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
            // On page 1: persist only page 1 fields
            $this->persistPage1Scores();
        } elseif ($this->currentPage == 2) {
            // On page 2: persist only page 2 fields
            $this->persistPage2Scores();
        }
    }

    /**
     * Persist only Page 1 scores (General Appearance, Manner of Speaking, Physical Conditions, Alertness)
     */
    protected function persistPage1Scores(): void
    {
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

        // If no interview record yet exists, create one with the page‑1 fields so
        // that subsequent page switches can persist safely. This mirrors the
        // logic in persistInterviewScores but scoped to the first page.
        if (!$panelAssignment->interview_id) {
            // create record with all non-nullable fields populated;
            // missing scores default to zero so the insert never fails.
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
            'general_appearance' => $this->general_appearance,
            'manner_of_speaking' => $this->manner_of_speaking,
            'physical_conditions' => $this->physical_conditions,
            'alertness' => $this->alertness,
        ]);
    }

    /**
     * Persist only Page 2 scores (Self Confidence, Ability to Present Ideas, Maturity of Judgement)
     */
    protected function persistPage2Scores(): void
    {
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

        // If an interview record exists update only page‑2 fields. Otherwise
        // create a new interview entry to hold the incoming scores (page‑1
        // values may still be zero but the component state keeps them until
        // saved elsewhere).
        if (!$panelAssignment->interview_id) {
            // make sure page‑1 fields are also set even though they haven't
            // been rated yet (zero indicates unanswered).
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
            'self_confidence' => $this->self_confidence,
            'ability_to_present_ideas' => $this->ability_to_present_ideas,
            'maturity_of_judgement' => $this->maturity_of_judgement,
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

        // Restore page from session (set when returning from Performance), then from param
        if (session()->has('returnPage')) {
            $this->currentPage = (int) session()->pull('returnPage');
        } elseif ($page !== null) {
            $this->currentPage = (int) $page;
        }

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

            // Load existing interview scores back into component properties.
            // Always cast to int — the DB may return strings, and Livewire radio
            // binding uses strict comparison, so mismatched types cause wrong
            // radio buttons to appear selected (or none at all).
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

    /**
     * Persist all interview scores to the DB (used when leaving the component entirely)
     */
    protected function persistInterviewScores(): void
    {
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

        $data = [
            'general_appearance'       => $this->general_appearance,
            'manner_of_speaking'       => $this->manner_of_speaking,
            'physical_conditions'      => $this->physical_conditions,
            'alertness'                => $this->alertness,
            'self_confidence'          => $this->self_confidence,
            'ability_to_present_ideas' => $this->ability_to_present_ideas,
            'maturity_of_judgement'    => $this->maturity_of_judgement,
            'total_score'              => $this->general_appearance
                                        + $this->manner_of_speaking
                                        + $this->physical_conditions
                                        + $this->alertness
                                        + $this->self_confidence
                                        + $this->ability_to_present_ideas
                                        + $this->maturity_of_judgement,
        ];

        if ($panelAssignment->interview_id) {
            ModelsInterview::where('id', $panelAssignment->interview_id)->update($data);
        } else {
            $interview = ModelsInterview::create($data);
            $panelAssignment->update(['interview_id' => $interview->id]);
        }
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

            // Persist page-1 scores to DB before advancing
            $this->persistPage1Scores();
        }

        $this->currentPage++;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            // updatingCurrentPage already persists the correct page data
            $this->currentPage--;
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

        $applicantPosition = $this->evaluation->jobApplication->position->name ?? null;

        if ($applicantPosition === 'Instructor I') {
            $this->saveInterview();
        } else {
            $this->dispatch('show-swal-confirm');
        }
    }

    public function saveInterview()
    {
        $this->calculateTotal();

        $user  = Auth::user();
        $panel = $user->panel;

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

        $applicantPosition = $this->evaluation->jobApplication->position->name ?? null;

        if ($applicantPosition === 'Instructor I') {
            return redirect()->route('panel.performance', [
                'evaluationId' => $this->evaluationId,
                'interviewId'  => $interview->id,
            ]);
        } else {
            if ($panel && $panelAssignment) {
                $panelAssignment->update(['status' => 'complete']);
            }

            $this->dispatch('interview-saved');
        }
    }

    public function render()
    {
        return view('livewire.panel.interview');
    }
}

