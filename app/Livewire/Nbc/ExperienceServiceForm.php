<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use App\Models\NbcAssignment;
use App\Models\ExperienceService;
use App\Models\NbcCommittee;
use App\Models\Evaluation;

class ExperienceServiceForm extends Component
{
    public $assignment;
    public $evaluation;
    public $experienceService;
    public $applicant;
    public $position;
    public $jobApplication;
    public $evaluationId;
    
    // Form fields - Only RS values are editable
    public $rs_2_1_1 = 0;
    public $rs_2_1_2 = 0;
    public $rs_2_2_1 = 0;
    public $rs_2_3_1 = 0;
    public $rs_2_3_2 = 0;
    
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

        $this->jobApplication = $this->evaluation->jobApplication;
        $this->applicant = $this->jobApplication->applicant;
        $this->position = $this->jobApplication->position;
        
        // Get or create NBC committee for current user
        $nbcCommittee = NbcCommittee::where('user_id', auth()->id())->first();
        
        if (!$nbcCommittee) {
            abort(403, 'You are not assigned as an NBC committee member.');
        }
        
        // Get or create assignment automatically
        $this->assignment = NbcAssignment::firstOrCreate([
            'nbc_committee_id' => $nbcCommittee->id,
            'evaluation_id' => $this->evaluation->id,
        ], [
            'status' => 'pending',
            'type' => $nbcCommittee->position === 'evaluator' ? 'evaluate' : 'verify',
        ]);
        
        // Get or create experience service
        if ($this->assignment->experienceService) {
            $this->experienceService = $this->assignment->experienceService;
            $this->loadExistingScores();
        } else {
            $this->experienceService = $this->createExperienceService();
        }
    }

    protected function createExperienceService()
    {
        $service = ExperienceService::create([]);

        $this->assignment->update([
            'experience_service_id' => $service->id
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

        // Calculate EP subtotal: MIN(RS subtotal, 25)
        $epSubtotal = $this->epSubtotal;

        // Store individual RS values and the calculated EP subtotal
        $this->experienceService->update([
            'rs_2_1_1' => $this->rs_2_1_1,
            'rs_2_1_2' => $this->rs_2_1_2,
            'rs_2_2_1' => $this->rs_2_2_1,
            'rs_2_3_1' => $this->rs_2_3_1,
            'rs_2_3_2' => $this->rs_2_3_2,
            'subtotal' => $epSubtotal, // This is the EP value
            // Set individual EP values to null since we're using subtotal only
            'ep_2_1_1' => null,
            'ep_2_1_2' => null,
            'ep_2_2_1' => null,
            'ep_2_3_1' => null,
            'ep_2_3_2' => null,
        ]);
    }

    public function toggleApplicantModal()
    {
        $this->showApplicantModal = !$this->showApplicantModal;
    }

    public function previous()
    {
        // Auto-save before navigating back
        $this->saveData();
        
        session()->flash('message', 'Experience and service saved successfully.');
        
        // Navigate back to educational qualification form
        return redirect()->route('nbc.educational-qualification', ['evaluationId' => $this->evaluation->id]);
    }

    public function next()
    {
        // Auto-save before navigating
        $this->saveData();
        
        session()->flash('message', 'Experience and service saved successfully.');
        
        // Navigate to professional development form
        return redirect()->route('nbc.professional-development', ['evaluationId' => $this->evaluation->id]);
    }

    public function render()
    {
        return view('livewire.nbc.experience-service-form');
    }
}