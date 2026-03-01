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

    // Whether the current user is a verifier
    public bool $isVerifier = false;

    // Evaluator's scores (read-only reference for verifiers)
    public $evaluator_rs_1_1 = null;
    public $evaluator_rs_1_2 = null;
    public $evaluator_rs_1_3 = null;
    public bool $evaluatorScoresExist = false;

    // Form fields - RS values editable by both evaluator and verifier
    public $rs_1_1 = 0;
    public $rs_1_2 = 0;
    public $rs_1_3 = 0;
    public $requirements_file;
    public $existing_file_path = null;

    public $showApplicantModal = false;

    // Computed property for RS subtotal
    public function getRsSubtotalProperty()
    {
        return (float) $this->rs_1_1
             + (float) $this->rs_1_2
             + (float) $this->rs_1_3;
    }

    // Computed property for EP (overall) - MIN(RS subtotal, 85)
    public function getEpSubtotalProperty()
    {
        return min($this->rsSubtotal, 85);
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

        // Get NBC committee for current user
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        if (!$nbcCommittee) {
            abort(403, 'You are not assigned as an NBC committee member.');
        }

        $this->isVerifier = $nbcCommittee->isVerifier();

        // If verifier, load the evaluator's scores for read-only reference
        if ($this->isVerifier) {
            $evaluatorAssignment = NbcAssignment::where('evaluation_id', $this->evaluation->id)
                ->where('type', 'evaluate')
                ->with('educationalQualification')
                ->first();

            if ($evaluatorAssignment && $evaluatorAssignment->educationalQualification) {
                $eq = $evaluatorAssignment->educationalQualification;
                $this->evaluator_rs_1_1     = $eq->rs_1_1;
                $this->evaluator_rs_1_2     = $eq->rs_1_2;
                $this->evaluator_rs_1_3     = $eq->rs_1_3;
                $this->evaluatorScoresExist = true;
            }
        }

        // Get or create assignment automatically
        $this->assignment = NbcAssignment::firstOrCreate([
            'nbc_committee_id' => $nbcCommittee->id,
            'evaluation_id'    => $this->evaluation->id,
        ], [
            'status' => 'pending',
            'type'   => $nbcCommittee->position === 'evaluator' ? 'evaluate' : 'verify',
        ]);

        // Get or create educational qualification
        if ($this->assignment->educationalQualification) {
            $this->educationalQualification = $this->assignment->educationalQualification;
            $this->loadExistingScores();
        } else {
            $this->educationalQualification = $this->createEducationalQualification();

            // Pre-fill verifier's editable fields with evaluator scores as a starting point
            if ($this->isVerifier && $this->evaluatorScoresExist) {
                $this->rs_1_1 = $this->evaluator_rs_1_1 ?? 0;
                $this->rs_1_2 = $this->evaluator_rs_1_2 ?? 0;
                $this->rs_1_3 = $this->evaluator_rs_1_3 ?? 0;
            }
        }
    }

    protected function createEducationalQualification()
    {
        $qualification = EducationalQualification::create([]);

        $this->assignment->update([
            'educational_qualification_id' => $qualification->id,
        ]);

        return $qualification;
    }

    protected function loadExistingScores()
    {
        $this->rs_1_1 = $this->educationalQualification->rs_1_1 ?? 0;
        $this->rs_1_2 = $this->educationalQualification->rs_1_2 ?? 0;
        $this->rs_1_3 = $this->educationalQualification->rs_1_3 ?? 0;
    }

    public function save()
    {
        $this->validate([
            'rs_1_1' => 'required|numeric|min:0|max:85',
            'rs_1_2' => 'required|numeric|min:0|max:85',
            'rs_1_3' => 'required|numeric|min:0|max:10',
        ]);

        $epSubtotal = $this->epSubtotal;

        $this->educationalQualification->update([
            'rs_1_1'   => $this->rs_1_1,
            'rs_1_2'   => $this->rs_1_2,
            'rs_1_3'   => $this->rs_1_3,
            'subtotal' => $epSubtotal,
            'ep_1_1'   => null,
            'ep_1_2'   => null,
            'ep_1_3'   => null,
        ]);

        $this->assignment->updateNbcScores();
    }

    /**
     * Generate base64 encoded PDF for viewing in new tab
     */
    public function getFileDataUrl()
    {
        $encryptionService = new FileEncryptionService();

        if (!$this->existing_file_path || !$encryptionService->fileExists($this->existing_file_path)) {
            return null;
        }

        try {
            $decryptedContents = $encryptionService->decryptFile($this->existing_file_path);
            $base64 = base64_encode($decryptedContents);
            return 'data:application/pdf;base64,' . $base64;
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
        $this->save();
        session()->flash('message', 'Educational qualification saved successfully.');
        return redirect()->route('nbc.experience-service', ['evaluationId' => $this->evaluation->id]);
    }

    public function render()
    {
        return view('livewire.nbc.educational-qualification-form');
    }
}