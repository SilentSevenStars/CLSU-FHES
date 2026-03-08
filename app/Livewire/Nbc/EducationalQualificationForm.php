<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use App\Models\NbcAssignment;
use App\Models\EducationalQualification;
use App\Models\NbcCommittee;
use App\Models\Evaluation;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;

class EducationalQualificationForm extends Component
{
    public $assignment;
    public $evaluation;
    public $educationalQualification;
    public $applicant;
    public $position;
    public $jobApplication;
    public $evaluationId;
    public $existing_file_path = null;
    public $showApplicantModal  = false;

    // ── Scores from the applicant's most recent PAST completed evaluation ──
    // These are read-only. They come from a DIFFERENT (older) nbc_assignment
    // for the SAME APPLICANT (any previous job application / position).
    public $prev_q1_1 = 0;
    public $prev_q1_2 = 0;
    public $prev_q1_3 = 0;

    // ── What this NBC member enters for THIS evaluation ──
    // Saved to educational_qualifications immediately on Save / Next / Previous
    // so navigating away and back restores them.
    public $new_q1_1 = 0;
    public $new_q1_2 = 0;
    public $new_q1_3 = 0;

    // RS = prev (past eval) + new (current input, live)
    public function getRsTotalProperty(): float
    {
        return (float)$this->prev_q1_1 + (float)$this->new_q1_1
             + (float)$this->prev_q1_2 + (float)$this->new_q1_2
             + (float)$this->prev_q1_3 + (float)$this->new_q1_3;
    }

    // EP = MIN(RS, 85)
    public function getEpSubtotalProperty(): float
    {
        return min($this->rsTotal, 85);
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

        // Get or create THIS assignment
        $this->assignment = NbcAssignment::firstOrCreate([
            'nbc_committee_id' => $nbcCommittee->id,
            'evaluation_id'    => $this->evaluation->id,
        ], [
            'status'          => 'pending',
            'evaluation_date' => now(),
        ]);

        // Get or create EducationalQualification for THIS assignment
        if ($this->assignment->educational_qualification_id) {
            $this->educationalQualification = EducationalQualification::find(
                $this->assignment->educational_qualification_id
            );
        }

        if (!$this->educationalQualification) {
            $this->educationalQualification = EducationalQualification::create([
                'q1_1' => 0, 'q1_2' => 0, 'q1_3' => 0, 'subtotal' => 0,
            ]);
            $this->assignment->update([
                'educational_qualification_id' => $this->educationalQualification->id,
            ]);
        }

        // ── Load PREVIOUS evaluation scores (prev_) ──
        // Find the most recent COMPLETED assignment for the SAME APPLICANT
        // by this NBC member, with an evaluation_date BEFORE this assignment's datetime.
        // This covers any previous job application (Instructor I, II, etc.)
        $previousAssignment = NbcAssignment::where('nbc_committee_id', $nbcCommittee->id)
            ->where('id', '!=', $this->assignment->id)
            ->where('status', 'complete')
            ->whereHas('evaluation.jobApplication', function ($q) {
                $q->where('applicant_id', $this->applicant->id);
            })
            ->where('evaluation_date', '<', $this->assignment->evaluation_date)
            ->orderByDesc('evaluation_date')
            ->first();

        if ($previousAssignment && $previousAssignment->educational_qualification_id) {
            $prevEq = EducationalQualification::find($previousAssignment->educational_qualification_id);
            if ($prevEq) {
                $this->prev_q1_1 = (float)($prevEq->q1_1 ?? 0);
                $this->prev_q1_2 = (float)($prevEq->q1_2 ?? 0);
                $this->prev_q1_3 = (float)($prevEq->q1_3 ?? 0);
            }
        }

        // ── Load THIS evaluation's already-saved inputs into new_ fields ──
        // This makes inputs survive navigating away and back (prev button from ES form).
        $this->new_q1_1 = (float)($this->educationalQualification->q1_1 ?? 0);
        $this->new_q1_2 = (float)($this->educationalQualification->q1_2 ?? 0);
        $this->new_q1_3 = (float)($this->educationalQualification->q1_3 ?? 0);
    }

    /**
     * Persist current new_ inputs to the DB.
     * We store ONLY the current session's inputs (not prev + new accumulated).
     * The final total (prev + new) is computed at display/submit time.
     */
    protected function persistCurrentInputs(): void
    {
        $this->validate([
            'new_q1_1' => 'required|numeric|min:0',
            'new_q1_2' => 'required|numeric|min:0',
            'new_q1_3' => 'required|numeric|min:0',
        ]);

        $this->educationalQualification->update([
            'q1_1'     => (float)$this->new_q1_1,
            'q1_2'     => (float)$this->new_q1_2,
            'q1_3'     => (float)$this->new_q1_3,
            'subtotal' => $this->epSubtotal, // prev+new capped at 85
        ]);
    }

    public function save()
    {
        $this->persistCurrentInputs();
        session()->flash('message', 'Educational qualification saved.');
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

    public function return()
    {
        return redirect()->route('nbc.dashboard');
    }

    public function next()
    {
        $this->persistCurrentInputs();
        return redirect()->route('nbc.experience-service', ['evaluationId' => $this->evaluation->id]);
    }

    public function render()
    {
        return view('livewire.nbc.educational-qualification-form');
    }
}