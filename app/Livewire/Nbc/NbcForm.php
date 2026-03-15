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
    public $existing_file_path = null;
    public $showApplicantModal = false;

    // ── Previous evaluation scores (read-only) ──
    public float $prev_educational_qualification = 0;
    public float $prev_experience = 0;
    public float $prev_professional_development = 0;

    // ── New points entered for THIS evaluation ──
    public $new_educational_qualification = 0;
    public $new_experience = 0;
    public $new_professional_development = 0;

    // ── Computed totals ──

    public function getTotalPreviousPointsProperty(): float
    {
        return $this->prev_educational_qualification
             + $this->prev_experience
             + $this->prev_professional_development;
    }

    public function getEqTotalProperty(): float
    {
        return min(
            $this->prev_educational_qualification + (float)$this->new_educational_qualification,
            85
        );
    }

    public function getEsTotalProperty(): float
    {
        return min(
            $this->prev_experience + (float)$this->new_experience,
            25
        );
    }

    public function getPdTotalProperty(): float
    {
        return min(
            $this->prev_professional_development + (float)$this->new_professional_development,
            90
        );
    }

    // Total Points = prev + new per section (each capped), summed
    public function getTotalPointsProperty(): float
    {
        return $this->eqTotal + $this->esTotal + $this->pdTotal;
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

        // Get or create assignment
        $this->assignment = NbcAssignment::firstOrCreate([
            'nbc_committee_id' => $nbcCommittee->id,
            'evaluation_id'    => $this->evaluation->id,
        ], [
            'status'          => 'pending',
            'evaluation_date' => now(),
        ]);

        // Get or create NBC record
        if ($this->assignment->nbc) {
            $this->nbc = $this->assignment->nbc;
        } else {
            $this->nbc = $this->createNbc();
        }

        // ── Load previous completed assignment scores (prev_) ──
        $previousAssignment = NbcAssignment::where('nbc_committee_id', $nbcCommittee->id)
            ->where('id', '!=', $this->assignment->id)
            ->where('status', 'complete')
            ->whereHas('evaluation.jobApplication', function ($q) {
                $q->where('applicant_id', $this->applicant->id);
            })
            ->orderByDesc('created_at')
            ->with('nbc')
            ->first();

        if ($previousAssignment && $previousAssignment->nbc) {
            $this->prev_educational_qualification = (float)($previousAssignment->nbc->educational_qualification ?? 0);
            $this->prev_experience                = (float)($previousAssignment->nbc->experience ?? 0);
            $this->prev_professional_development  = (float)($previousAssignment->nbc->professional_development ?? 0);
        }

        // ── Load THIS evaluation's already-saved inputs into new_ fields ──
        $this->new_educational_qualification = (float)($this->nbc->educational_qualification ?? 0);
        $this->new_experience                = (float)($this->nbc->experience ?? 0);
        $this->new_professional_development  = (float)($this->nbc->professional_development ?? 0);
    }

    protected function createNbc()
    {
        $nbc = Nbc::create([
            'educational_qualification' => 0,
            'experience'                => 0,
            'professional_development'  => 0,
            'total_score'               => 0,
        ]);

        $this->assignment->update(['nbc_id' => $nbc->id]);

        return $nbc;
    }

    public function save()
    {
        $this->validate([
            'new_educational_qualification' => 'required|numeric|min:0|max:85',
            'new_experience'                => 'required|numeric|min:0|max:25',
            'new_professional_development'  => 'required|numeric|min:0|max:90',
        ]);

        $this->nbc->update([
            'educational_qualification' => (float)$this->new_educational_qualification,
            'experience'                => (float)$this->new_experience,
            'professional_development'  => (float)$this->new_professional_development,
            'total_score'               => $this->totalPoints,
        ]);

        session()->flash('message', 'NBC scores saved successfully.');
    }

    public function submit()
    {
        $this->save();
        $this->assignment->update(['status' => 'complete']);
        session()->flash('message', 'NBC evaluation completed successfully.');
        return redirect()->route('nbc.dashboard');
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

    public function render()
    {
        return view('livewire.nbc.nbc-form');
    }
}