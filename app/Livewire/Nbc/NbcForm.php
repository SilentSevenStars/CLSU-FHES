<?php

namespace App\Livewire\Nbc;

use App\Models\Evaluation;
use App\Models\Nbc;
use App\Models\NbcAssignment;
use App\Models\NbcCommittee;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NbcForm extends Component
{
    public $assignment;
    public $evaluation;
    public $nbc;
    public $applicant;
    public $position;
    public $jobApplication;
    public $evaluationId;
    
    // Form fields - Direct NBC scores
    public $educational_qualification = 0;
    public $experience = 0;
    public $professional_development = 0;
    public $requirements_file;
    public $existing_file_path = null;
    
    public $showApplicantModal = false;

    // Computed property for total score
    public function getTotalScoreProperty()
    {
        return (float) $this->educational_qualification
             + (float) $this->experience
             + (float) $this->professional_development;
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
        $this->existing_file_path = $this->jobApplication->requirements_file;
        
        // Get or create NBC committee for current user
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();
        
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
        
        // Get or create NBC record
        if ($this->assignment->nbc) {
            $this->nbc = $this->assignment->nbc;
            $this->loadExistingScores();
        } else {
            $this->nbc = $this->createNbc();
        }
    }

    protected function createNbc()
    {
        $nbc = Nbc::create([
            'educational_qualification' => 0,
            'experience' => 0,
            'professional_development' => 0,
            'total_score' => 0,
        ]);

        $this->assignment->update([
            'nbc_id' => $nbc->id
        ]);

        return $nbc;
    }

    protected function loadExistingScores()
    {
        $this->educational_qualification = $this->nbc->educational_qualification ?? 0;
        $this->experience = $this->nbc->experience ?? 0;
        $this->professional_development = $this->nbc->professional_development ?? 0;
    }

    public function save()
    {
        $this->validate([
            'educational_qualification' => 'required|numeric|min:0|max:85',
            'experience' => 'required|numeric|min:0|max:25',
            'professional_development' => 'required|numeric|min:0|max:90',
        ]);

        // Calculate total score
        $totalScore = $this->totalScore;

        // Update NBC record
        $this->nbc->update([
            'educational_qualification' => $this->educational_qualification,
            'experience' => $this->experience,
            'professional_development' => $this->professional_development,
            'total_score' => $totalScore,
        ]);

        session()->flash('message', 'NBC scores saved successfully.');
    }

    public function submit()
    {
        $this->save();
        
        // Mark assignment as complete
        $this->assignment->update(['status' => 'complete']);

        session()->flash('message', 'NBC evaluation completed successfully.');
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

    public function toggleApplicantModal()
    {
        $this->showApplicantModal = !$this->showApplicantModal;
    }

    public function return()
    {
        return redirect()->route('nbc.dashboard');
    }
    public function render()
    {
        return view('livewire.nbc.nbc-form');
    }
}
