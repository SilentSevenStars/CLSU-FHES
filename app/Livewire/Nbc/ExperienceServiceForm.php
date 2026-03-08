<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use App\Models\NbcAssignment;
use App\Models\ExperienceService;
use App\Models\NbcCommittee;
use App\Models\Evaluation;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;

class ExperienceServiceForm extends Component
{
    public $assignment;
    public $evaluation;
    public $experienceService;
    public $applicant;
    public $position;
    public $jobApplication;
    public $evaluationId;
    public $existing_file_path = null;
    public $showApplicantModal  = false;

    // ── Scores from the applicant's most recent PAST completed evaluation ──
    // Read-only reference. Comes from a different (older) nbc_assignment
    // for the SAME APPLICANT (any previous job application / position).
    public $prev_q2_1_1 = 0;
    public $prev_q2_1_2 = 0;
    public $prev_q2_2_1 = 0;
    public $prev_q2_3_1 = 0;
    public $prev_q2_3_2 = 0;

    // ── What this NBC member enters for THIS evaluation ──
    // Saved to DB on next/previous/save so navigating back restores them.
    public $new_q2_1_1 = 0;
    public $new_q2_1_2 = 0;
    public $new_q2_2_1 = 0;
    public $new_q2_3_1 = 0;
    public $new_q2_3_2 = 0;

    // RS = prev (past eval) + new (current input, live)
    public function getRsTotalProperty(): float
    {
        return ((float)$this->prev_q2_1_1 + (float)$this->new_q2_1_1)
             + ((float)$this->prev_q2_1_2 + (float)$this->new_q2_1_2)
             + ((float)$this->prev_q2_2_1 + (float)$this->new_q2_2_1)
             + ((float)$this->prev_q2_3_1 + (float)$this->new_q2_3_1)
             + ((float)$this->prev_q2_3_2 + (float)$this->new_q2_3_2);
    }

    // EP = MIN(RS, 25)
    public function getEpSubtotalProperty(): float
    {
        return min($this->rsTotal, 25);
    }

    public function mount($evaluationId = null)
    {
        $this->evaluationId = $evaluationId;

        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])->findOrFail($this->evaluationId);

        $this->jobApplication     = $this->evaluation->jobApplication;
        $this->applicant          = $this->jobApplication->applicant;
        $this->position           = $this->jobApplication->position;
        $this->existing_file_path = $this->jobApplication->requirements_file;

        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();
        if (!$nbcCommittee) {
            abort(403, 'You are not assigned as an NBC committee member.');
        }

        $this->assignment = NbcAssignment::firstOrCreate([
            'nbc_committee_id' => $nbcCommittee->id,
            'evaluation_id'    => $this->evaluation->id,
        ], [
            'status'          => 'pending',
            'evaluation_date' => now(),
        ]);

        if ($this->assignment->experience_service_id) {
            $this->experienceService = ExperienceService::find($this->assignment->experience_service_id);
        }

        if (!$this->experienceService) {
            $this->experienceService = ExperienceService::create([
                'q2_1_1' => 0, 'q2_1_2' => 0, 'q2_2_1' => 0,
                'q2_3_1' => 0, 'q2_3_2' => 0, 'subtotal' => 0,
            ]);
            $this->assignment->update(['experience_service_id' => $this->experienceService->id]);
        }

        // ── Load PREVIOUS evaluation scores (prev_) ──
        // Most recent COMPLETED assignment for the SAME APPLICANT (any position),
        // by this NBC member, with an evaluation_date strictly before this one.
        $previousAssignment = NbcAssignment::where('nbc_committee_id', $nbcCommittee->id)
            ->where('id', '!=', $this->assignment->id)
            ->where('status', 'complete')
            ->whereHas('evaluation.jobApplication', function ($q) {
                $q->where('applicant_id', $this->applicant->id);
            })
            ->where('evaluation_date', '<', $this->assignment->evaluation_date)
            ->orderByDesc('evaluation_date')
            ->first();

        if ($previousAssignment && $previousAssignment->experience_service_id) {
            $prevEs = ExperienceService::find($previousAssignment->experience_service_id);
            if ($prevEs) {
                $this->prev_q2_1_1 = (float)($prevEs->q2_1_1 ?? 0);
                $this->prev_q2_1_2 = (float)($prevEs->q2_1_2 ?? 0);
                $this->prev_q2_2_1 = (float)($prevEs->q2_2_1 ?? 0);
                $this->prev_q2_3_1 = (float)($prevEs->q2_3_1 ?? 0);
                $this->prev_q2_3_2 = (float)($prevEs->q2_3_2 ?? 0);
            }
        }

        // ── Load THIS evaluation's already-saved inputs into new_ fields ──
        // Ensures inputs survive navigating away and back.
        $this->new_q2_1_1 = (float)($this->experienceService->q2_1_1 ?? 0);
        $this->new_q2_1_2 = (float)($this->experienceService->q2_1_2 ?? 0);
        $this->new_q2_2_1 = (float)($this->experienceService->q2_2_1 ?? 0);
        $this->new_q2_3_1 = (float)($this->experienceService->q2_3_1 ?? 0);
        $this->new_q2_3_2 = (float)($this->experienceService->q2_3_2 ?? 0);
    }

    /**
     * Persist current new_ inputs to DB.
     * Stores ONLY this session's inputs (not prev+new accumulated).
     * Final total (prev+new) is computed at display/submit time.
     */
    protected function persistCurrentInputs(): void
    {
        $this->validate([
            'new_q2_1_1' => 'required|numeric|min:0',
            'new_q2_1_2' => 'required|numeric|min:0',
            'new_q2_2_1' => 'required|numeric|min:0',
            'new_q2_3_1' => 'required|numeric|min:0',
            'new_q2_3_2' => 'required|numeric|min:0',
        ]);

        $this->experienceService->update([
            'q2_1_1'   => (float)$this->new_q2_1_1,
            'q2_1_2'   => (float)$this->new_q2_1_2,
            'q2_2_1'   => (float)$this->new_q2_2_1,
            'q2_3_1'   => (float)$this->new_q2_3_1,
            'q2_3_2'   => (float)$this->new_q2_3_2,
            'subtotal' => $this->epSubtotal, // prev+new capped at 25
        ]);
    }

    public function getFileDataUrl()
    {
        $encryptionService = new FileEncryptionService();
        if (!$this->existing_file_path || !$encryptionService->fileExists($this->existing_file_path)) {
            return null;
        }
        try {
            return 'data:application/pdf;base64,' . base64_encode(
                $encryptionService->decryptFile($this->existing_file_path)
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function toggleApplicantModal()
    {
        $this->showApplicantModal = !$this->showApplicantModal;
    }

    public function previous()
    {
        $this->persistCurrentInputs();
        return redirect()->route('nbc.educational-qualification', ['evaluationId' => $this->evaluation->id]);
    }

    public function next()
    {
        $this->persistCurrentInputs();
        return redirect()->route('nbc.professional-development', ['evaluationId' => $this->evaluation->id]);
    }

    public function render()
    {
        return view('livewire.nbc.experience-service-form');
    }
}