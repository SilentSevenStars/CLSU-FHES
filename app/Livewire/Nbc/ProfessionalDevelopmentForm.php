<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use App\Models\NbcAssignment;
use App\Models\ProfessionalDevelopment;
use App\Models\NbcCommittee;
use App\Models\Evaluation;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;

class ProfessionalDevelopmentForm extends Component
{
    public $assignment;
    public $evaluation;
    public ProfessionalDevelopment $professionalDevelopment;
    public $applicant;
    public $position;
    public $jobApplication;
    public $evaluationId;
    public $currentPage = 1;
    public $requirements_file;
    public $existing_file_path = null;

    // Section 3.1 - Inventions and Publications
    public $rs_3_1_1 = 0;
    public $rs_3_1_2_a = 0;
    public $rs_3_1_2_c = 0;
    public $rs_3_1_2_d = 0;
    public $rs_3_1_2_e = 0;
    public $rs_3_1_2_f = 0;
    public $rs_3_1_3_a = 0;
    public $rs_3_1_3_b = 0;
    public $rs_3_1_3_c = 0;
    public $rs_3_1_4 = 0;

    // Section 3.2.1 - Training and Seminars
    public $rs_3_2_1_1_a = 0;
    public $rs_3_2_1_1_b = 0;
    public $rs_3_2_1_1_c = 0;
    public $rs_3_2_1_2 = 0;
    public $rs_3_2_1_3_a = 0;
    public $rs_3_2_1_3_b = 0;
    public $rs_3_2_1_3_c = 0;

    // Section 3.2.2 - Expert Services
    public $rs_3_2_2_1_a = 0;
    public $rs_3_2_2_1_b = 0;
    public $rs_3_2_2_1_c = 0;
    public $rs_3_2_2_2 = 0;
    public $rs_3_2_2_3 = 0;
    public $rs_3_2_2_4 = 0;
    public $rs_3_2_2_5 = 0;
    public $rs_3_2_2_6 = 0;
    public $rs_3_2_2_7 = 0;

    // Section 3.3 - Academic Distinctions
    public $rs_3_3_1_a = 0;
    public $rs_3_3_1_b = 0;
    public $rs_3_3_1_c = 0;
    public $rs_3_3_2 = 0;
    public $rs_3_3_3_a_doctorate = 0;
    public $rs_3_3_3_a_masters = 0;
    public $rs_3_3_3_a_nondegree = 0;
    public $rs_3_3_3_b_doctorate = 0;
    public $rs_3_3_3_b_masters = 0;
    public $rs_3_3_3_b_nondegree = 0;
    public $rs_3_3_3_c_doctorate = 0;
    public $rs_3_3_3_c_masters = 0;
    public $rs_3_3_3_c_nondegree = 0;
    public $rs_3_3_3_d_doctorate = 0;
    public $rs_3_3_3_d_masters = 0;
    public $rs_3_3_3_e = 0;

    // Section 3.4 - Awards
    public $rs_3_4_a = 0;
    public $rs_3_4_b = 0;
    public $rs_3_4_c = 0;

    // Section 3.5 - Community Outreach
    public $rs_3_5_1 = 0;

    // Section 3.6 - Professional Examinations
    public $rs_3_6_1_a = 0;
    public $rs_3_6_1_b = 0;
    public $rs_3_6_1_c = 0;
    public $rs_3_6_1_d = 0;

    public $showApplicantModal = false;


    public function mount(int $evaluationId)
    {
        $this->evaluationId = $evaluationId;

        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])->findOrFail($evaluationId);

        $this->jobApplication = $this->evaluation->jobApplication;
        $this->applicant = $this->jobApplication->applicant;
        $this->position = $this->jobApplication->position;
        $this->existing_file_path = $this->jobApplication->requirements_file;

        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->firstOrFail();

        $this->assignment = NbcAssignment::firstOrCreate(
            [
                'nbc_committee_id' => $nbcCommittee->id,
                'evaluation_id' => $this->evaluation->id,
            ],
            [
                'status' => 'pending',
                'type' => $nbcCommittee->position === 'evaluator' ? 'evaluate' : 'verify',
            ]
        );

        $this->professionalDevelopment =
            $this->assignment->professionalDevelopment
            ?? ProfessionalDevelopment::create();

        $this->assignment->update([
            'professional_development_id' => $this->professionalDevelopment->id
        ]);

        $this->loadExistingScores();
    }

    protected function createProfessionalDevelopment()
    {
        $development = ProfessionalDevelopment::create([]);

        $this->assignment->update([
            'professional_development_id' => $development->id
        ]);

        return $development;
    }

    protected function loadExistingScores()
    {
        $pd = $this->professionalDevelopment;

        $this->rs_3_1_1 = $pd->rs_3_1_1 ?? 0;
        $this->rs_3_1_2_a = $pd->rs_3_1_2_a ?? 0;
        $this->rs_3_1_2_c = $pd->rs_3_1_2_c ?? 0;
        $this->rs_3_1_2_d = $pd->rs_3_1_2_d ?? 0;
        $this->rs_3_1_2_e = $pd->rs_3_1_2_e ?? 0;
        $this->rs_3_1_2_f = $pd->rs_3_1_2_f ?? 0;
        $this->rs_3_1_3_a = $pd->rs_3_1_3_a ?? 0;
        $this->rs_3_1_3_b = $pd->rs_3_1_3_b ?? 0;
        $this->rs_3_1_3_c = $pd->rs_3_1_3_c ?? 0;
        $this->rs_3_1_4 = $pd->rs_3_1_4 ?? 0;

        $this->rs_3_2_1_1_a = $pd->rs_3_2_1_1_a ?? 0;
        $this->rs_3_2_1_1_b = $pd->rs_3_2_1_1_b ?? 0;
        $this->rs_3_2_1_1_c = $pd->rs_3_2_1_1_c ?? 0;
        $this->rs_3_2_1_2 = $pd->rs_3_2_1_2 ?? 0;
        $this->rs_3_2_1_3_a = $pd->rs_3_2_1_3_a ?? 0;
        $this->rs_3_2_1_3_b = $pd->rs_3_2_1_3_b ?? 0;
        $this->rs_3_2_1_3_c = $pd->rs_3_2_1_3_c ?? 0;

        $this->rs_3_2_2_1_a = $pd->rs_3_2_2_1_a ?? 0;
        $this->rs_3_2_2_1_b = $pd->rs_3_2_2_1_b ?? 0;
        $this->rs_3_2_2_1_c = $pd->rs_3_2_2_1_c ?? 0;
        $this->rs_3_2_2_2 = $pd->rs_3_2_2_2 ?? 0;
        $this->rs_3_2_2_3 = $pd->rs_3_2_2_3 ?? 0;
        $this->rs_3_2_2_4 = $pd->rs_3_2_2_4 ?? 0;
        $this->rs_3_2_2_5 = $pd->rs_3_2_2_5 ?? 0;
        $this->rs_3_2_2_6 = $pd->rs_3_2_2_6 ?? 0;
        $this->rs_3_2_2_7 = $pd->rs_3_2_2_7 ?? 0;

        $this->rs_3_3_1_a = $pd->rs_3_3_1_a ?? 0;
        $this->rs_3_3_1_b = $pd->rs_3_3_1_b ?? 0;
        $this->rs_3_3_1_c = $pd->rs_3_3_1_c ?? 0;
        $this->rs_3_3_2 = $pd->rs_3_3_2 ?? 0;
        $this->rs_3_3_3_a_doctorate = $pd->rs_3_3_3_a_doctorate ?? 0;
        $this->rs_3_3_3_a_masters = $pd->rs_3_3_3_a_masters ?? 0;
        $this->rs_3_3_3_a_nondegree = $pd->rs_3_3_3_a_nondegree ?? 0;
        $this->rs_3_3_3_b_doctorate = $pd->rs_3_3_3_b_doctorate ?? 0;
        $this->rs_3_3_3_b_masters = $pd->rs_3_3_3_b_masters ?? 0;
        $this->rs_3_3_3_b_nondegree = $pd->rs_3_3_3_b_nondegree ?? 0;
        $this->rs_3_3_3_c_doctorate = $pd->rs_3_3_3_c_doctorate ?? 0;
        $this->rs_3_3_3_c_masters = $pd->rs_3_3_3_c_masters ?? 0;
        $this->rs_3_3_3_c_nondegree = $pd->rs_3_3_3_c_nondegree ?? 0;
        $this->rs_3_3_3_d_doctorate = $pd->rs_3_3_3_d_doctorate ?? 0;
        $this->rs_3_3_3_d_masters = $pd->rs_3_3_3_d_masters ?? 0;
        $this->rs_3_3_3_e = $pd->rs_3_3_3_e ?? 0;

        $this->rs_3_4_a = $pd->rs_3_4_a ?? 0;
        $this->rs_3_4_b = $pd->rs_3_4_b ?? 0;
        $this->rs_3_4_c = $pd->rs_3_4_c ?? 0;

        $this->rs_3_5_1 = $pd->rs_3_5_1 ?? 0;

        $this->rs_3_6_1_a = $pd->rs_3_6_1_a ?? 0;
        $this->rs_3_6_1_b = $pd->rs_3_6_1_b ?? 0;
        $this->rs_3_6_1_c = $pd->rs_3_6_1_c ?? 0;
        $this->rs_3_6_1_d = $pd->rs_3_6_1_d ?? 0;
    }

    public function getSubtotal31Property()
    {
        return $this->professionalDevelopment?->subtotal31 ?? 0;
    }

    public function getSubtotal321Property()
    {
        return $this->professionalDevelopment?->subtotal321 ?? 0;
    }

    public function getSubtotal322Property()
    {
        return $this->professionalDevelopment?->subtotal322 ?? 0;
    }

    public function getSubtotal33Property()
    {
        return $this->professionalDevelopment?->subtotal33 ?? 0;
    }

    public function getSubtotal34Property()
    {
        return $this->professionalDevelopment?->subtotal34 ?? 0;
    }

    public function getSubtotal35Property()
    {
        return $this->professionalDevelopment?->subtotal35 ?? 0;
    }

    public function getSubtotal36Property()
    {
        return $this->professionalDevelopment?->subtotal36 ?? 0;
    }

    // Cumulative page totals
    public function getPage1TotalProperty()
    {
        return $this->professionalDevelopment?->page1Total ?? 0;
    }

    public function getPage2TotalProperty()
    {
        return $this->professionalDevelopment?->page2Total ?? 0;
    }

    public function getPage3TotalProperty()
    {
        return $this->professionalDevelopment?->page3Total ?? 0;
    }

    public function getEpTotalProperty()
    {
        return $this->professionalDevelopment?->epScore ?? 0;
    }

    protected function saveData()
    {
        $this->validate([
            'rs_3_1_1' => 'numeric|min:0',
            // Add other validation rules as needed
        ]);

        $this->professionalDevelopment->update([
            'rs_3_1_1' => $this->rs_3_1_1,
            'rs_3_1_2_a' => $this->rs_3_1_2_a,
            'rs_3_1_2_c' => $this->rs_3_1_2_c,
            'rs_3_1_2_d' => $this->rs_3_1_2_d,
            'rs_3_1_2_e' => $this->rs_3_1_2_e,
            'rs_3_1_2_f' => $this->rs_3_1_2_f,
            'rs_3_1_3_a' => $this->rs_3_1_3_a,
            'rs_3_1_3_b' => $this->rs_3_1_3_b,
            'rs_3_1_3_c' => $this->rs_3_1_3_c,
            'rs_3_1_4' => $this->rs_3_1_4,

            'rs_3_2_1_1_a' => $this->rs_3_2_1_1_a,
            'rs_3_2_1_1_b' => $this->rs_3_2_1_1_b,
            'rs_3_2_1_1_c' => $this->rs_3_2_1_1_c,
            'rs_3_2_1_2' => $this->rs_3_2_1_2,
            'rs_3_2_1_3_a' => $this->rs_3_2_1_3_a,
            'rs_3_2_1_3_b' => $this->rs_3_2_1_3_b,
            'rs_3_2_1_3_c' => $this->rs_3_2_1_3_c,

            'rs_3_2_2_1_a' => $this->rs_3_2_2_1_a,
            'rs_3_2_2_1_b' => $this->rs_3_2_2_1_b,
            'rs_3_2_2_1_c' => $this->rs_3_2_2_1_c,
            'rs_3_2_2_2' => $this->rs_3_2_2_2,
            'rs_3_2_2_3' => $this->rs_3_2_2_3,
            'rs_3_2_2_4' => $this->rs_3_2_2_4,
            'rs_3_2_2_5' => $this->rs_3_2_2_5,
            'rs_3_2_2_6' => $this->rs_3_2_2_6,
            'rs_3_2_2_7' => $this->rs_3_2_2_7,

            'rs_3_3_1_a' => $this->rs_3_3_1_a,
            'rs_3_3_1_b' => $this->rs_3_3_1_b,
            'rs_3_3_1_c' => $this->rs_3_3_1_c,
            'rs_3_3_2' => $this->rs_3_3_2,

            'rs_3_3_3_a_doctorate' => $this->rs_3_3_3_a_doctorate,
            'rs_3_3_3_a_masters' => $this->rs_3_3_3_a_masters,
            'rs_3_3_3_a_nondegree' => $this->rs_3_3_3_a_nondegree,
            'rs_3_3_3_b_doctorate' => $this->rs_3_3_3_b_doctorate,
            'rs_3_3_3_b_masters' => $this->rs_3_3_3_b_masters,
            'rs_3_3_3_b_nondegree' => $this->rs_3_3_3_b_nondegree,
            'rs_3_3_3_c_doctorate' => $this->rs_3_3_3_c_doctorate,
            'rs_3_3_3_c_masters' => $this->rs_3_3_3_c_masters,
            'rs_3_3_3_c_nondegree' => $this->rs_3_3_3_c_nondegree,
            'rs_3_3_3_d_doctorate' => $this->rs_3_3_3_d_doctorate,
            'rs_3_3_3_d_masters' => $this->rs_3_3_3_d_masters,
            'rs_3_3_3_e' => $this->rs_3_3_3_e,

            'rs_3_4_a' => $this->rs_3_4_a,
            'rs_3_4_b' => $this->rs_3_4_b,
            'rs_3_4_c' => $this->rs_3_4_c,

            'rs_3_5_1' => $this->rs_3_5_1,

            'rs_3_6_1_a' => $this->rs_3_6_1_a,
            'rs_3_6_1_b' => $this->rs_3_6_1_b,
            'rs_3_6_1_c' => $this->rs_3_6_1_c,
            'rs_3_6_1_d' => $this->rs_3_6_1_d,
        ]);

        // Update NBC scores
        $this->assignment->updateNbcScores();
    }

    public function updated($property)
    {
        if (!$this->professionalDevelopment) {
            return;
        }

        if (str_starts_with($property, 'rs_')) {
            $this->professionalDevelopment->$property = $this->$property;
            $this->professionalDevelopment->save(); // ← ADD THIS LINE
        }
    }

    public function toggleApplicantModal()
    {
        $this->showApplicantModal = !$this->showApplicantModal;
    }

    public function previous()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        } else {
            // From Part 1, go back to experience service form
            return redirect()->route('nbc.experience-service', ['evaluationId' => $this->evaluation->id]);
        }
    }

    public function next()
    {
        if ($this->currentPage < 3) {
            $this->currentPage++;
        }
    }

    public function submit()
    {
        // Auto-save and complete
        $this->saveData();

        // Mark assignment as complete
        $this->assignment->update(['status' => 'complete']);

        session()->flash('message', 'Professional development evaluation completed successfully.');
        return redirect()->route('nbc.dashboard');
    }

    /**
     * Generate base64 encoded PDF for viewing in new tab
     */
    public function getFileDataUrl()
    {
        $encryptionService = new FileEncryptionService();

        // Check if file exists
        if (!$this->existing_file_path || !$encryptionService->fileExists($this->existing_file_path)) {
            return null;
        }

        try {
            // Decrypt file
            $decryptedContents = $encryptionService->decryptFile($this->existing_file_path);
            
            // Convert to base64
            $base64 = base64_encode($decryptedContents);
            
            // Return data URL
            return 'data:application/pdf;base64,' . $base64;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.nbc.professional-development-form');
    }
}
