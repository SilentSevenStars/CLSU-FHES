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

    // Whether the current user is a verifier
    public bool $isVerifier = false;

    // Evaluator's scores (read-only reference for verifiers)
    public $evaluator_rs_2_1_1 = null;
    public $evaluator_rs_2_1_2 = null;
    public $evaluator_rs_2_2_1 = null;
    public $evaluator_rs_2_3_1 = null;
    public $evaluator_rs_2_3_2 = null;
    public bool $evaluatorScoresExist = false;

    // Form fields - RS values editable by both evaluator and verifier
    public $rs_2_1_1 = 0;
    public $rs_2_1_2 = 0;
    public $rs_2_2_1 = 0;
    public $rs_2_3_1 = 0;
    public $rs_2_3_2 = 0;
    public $requirements_file;
    public $existing_file_path = null;

    public $showApplicantModal = false;

    // Computed property for RS subtotal
    public function getRsSubtotalProperty()
    {
        return (float) $this->rs_2_1_1
             + (float) $this->rs_2_1_2
             + (float) $this->rs_2_2_1
             + (float) $this->rs_2_3_1
             + (float) $this->rs_2_3_2;
    }

    // Computed property for EP (overall) - MIN(RS subtotal, 25)
    public function getEpSubtotalProperty()
    {
        return min($this->rsSubtotal, 25);
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
                ->with('experienceService')
                ->first();

            if ($evaluatorAssignment && $evaluatorAssignment->experienceService) {
                $es = $evaluatorAssignment->experienceService;
                $this->evaluator_rs_2_1_1   = $es->rs_2_1_1;
                $this->evaluator_rs_2_1_2   = $es->rs_2_1_2;
                $this->evaluator_rs_2_2_1   = $es->rs_2_2_1;
                $this->evaluator_rs_2_3_1   = $es->rs_2_3_1;
                $this->evaluator_rs_2_3_2   = $es->rs_2_3_2;
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

        // Get or create experience service
        if ($this->assignment->experienceService) {
            $this->experienceService = $this->assignment->experienceService;
            $this->loadExistingScores();
        } else {
            $this->experienceService = $this->createExperienceService();

            // Pre-fill verifier's editable fields with evaluator scores as a starting point
            if ($this->isVerifier && $this->evaluatorScoresExist) {
                $this->rs_2_1_1 = $this->evaluator_rs_2_1_1 ?? 0;
                $this->rs_2_1_2 = $this->evaluator_rs_2_1_2 ?? 0;
                $this->rs_2_2_1 = $this->evaluator_rs_2_2_1 ?? 0;
                $this->rs_2_3_1 = $this->evaluator_rs_2_3_1 ?? 0;
                $this->rs_2_3_2 = $this->evaluator_rs_2_3_2 ?? 0;
            }
        }
    }

    protected function createExperienceService()
    {
        $service = ExperienceService::create([]);

        $this->assignment->update([
            'experience_service_id' => $service->id,
        ]);

        return $service;
    }

    protected function loadExistingScores()
    {
        $this->rs_2_1_1 = $this->experienceService->rs_2_1_1 ?? 0;
        $this->rs_2_1_2 = $this->experienceService->rs_2_1_2 ?? 0;
        $this->rs_2_2_1 = $this->experienceService->rs_2_2_1 ?? 0;
        $this->rs_2_3_1 = $this->experienceService->rs_2_3_1 ?? 0;
        $this->rs_2_3_2 = $this->experienceService->rs_2_3_2 ?? 0;
    }

    protected function saveData()
    {
        $this->validate([
            'rs_2_1_1' => 'required|numeric|min:0|max:25',
            'rs_2_1_2' => 'required|numeric|min:0|max:25',
            'rs_2_2_1' => 'required|numeric|min:0|max:25',
            'rs_2_3_1' => 'required|numeric|min:0|max:25',
            'rs_2_3_2' => 'required|numeric|min:0|max:25',
        ]);

        $epSubtotal = $this->epSubtotal;

        $this->experienceService->update([
            'rs_2_1_1' => $this->rs_2_1_1,
            'rs_2_1_2' => $this->rs_2_1_2,
            'rs_2_2_1' => $this->rs_2_2_1,
            'rs_2_3_1' => $this->rs_2_3_1,
            'rs_2_3_2' => $this->rs_2_3_2,
            'subtotal' => $epSubtotal,
            'ep_2_1_1' => null,
            'ep_2_1_2' => null,
            'ep_2_2_1' => null,
            'ep_2_3_1' => null,
            'ep_2_3_2' => null,
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

    public function previous()
    {
        $this->saveData();
        session()->flash('message', 'Experience and service saved successfully.');
        return redirect()->route('nbc.educational-qualification', ['evaluationId' => $this->evaluation->id]);
    }

    public function next()
    {
        $this->saveData();
        session()->flash('message', 'Experience and service saved successfully.');
        return redirect()->route('nbc.professional-development', ['evaluationId' => $this->evaluation->id]);
    }

    public function render()
    {
        return view('livewire.nbc.experience-service-form');
    }
}