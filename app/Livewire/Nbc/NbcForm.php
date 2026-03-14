<?php

namespace App\Livewire\Nbc;

use App\Models\EducationalQualification;
use App\Models\ExperienceService;
use App\Models\NbcAssignment;
use App\Models\NbcCommittee;
use App\Models\ProfessionalDevelopment;
use App\Models\Evaluation;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NbcForm extends Component
{
    public $assignment;
    public $evaluation;
    public $applicant;
    public $position;
    public $jobApplication;
    public $evaluationId;
    public $existing_file_path = null;
    public $showApplicantModal  = false;

    // ── Models for this assignment ──
    public $educationalQualification;
    public $experienceService;
    public $professionalDevelopment;

    // ── Previous evaluation scores (read-only, from most recent completed assignment) ──
    // Educational Qualification
    public float $prev_q1_1 = 0;
    public float $prev_q1_2 = 0;
    public float $prev_q1_3 = 0;

    // Experience Service
    public float $prev_q2_1_1 = 0;
    public float $prev_q2_1_2 = 0;
    public float $prev_q2_2_1 = 0;
    public float $prev_q2_3_1 = 0;
    public float $prev_q2_3_2 = 0;

    // Professional Development (subtotal only — it is itself composed of sub-forms)
    public float $prev_pd_subtotal = 0;

    // ── New points entered for THIS evaluation ──
    // Educational Qualification
    public $new_q1_1 = 0;
    public $new_q1_2 = 0;
    public $new_q1_3 = 0;

    // Experience Service
    public $new_q2_1_1 = 0;
    public $new_q2_1_2 = 0;
    public $new_q2_2_1 = 0;
    public $new_q2_3_1 = 0;
    public $new_q2_3_2 = 0;

    // Professional Development (direct subtotal input on this form)
    public $new_pd_subtotal = 0;

    // ── Computed totals ──

    // Educational Qualification
    public function getEqRsTotalProperty(): float
    {
        return (float)$this->prev_q1_1 + (float)$this->new_q1_1
             + (float)$this->prev_q1_2 + (float)$this->new_q1_2
             + (float)$this->prev_q1_3 + (float)$this->new_q1_3;
    }

    public function getEqSubtotalProperty(): float
    {
        return min($this->eqRsTotal, 85);
    }

    // Experience Service
    public function getEsRsTotalProperty(): float
    {
        return (float)$this->prev_q2_1_1 + (float)$this->new_q2_1_1
             + (float)$this->prev_q2_1_2 + (float)$this->new_q2_1_2
             + (float)$this->prev_q2_2_1 + (float)$this->new_q2_2_1
             + (float)$this->prev_q2_3_1 + (float)$this->new_q2_3_1
             + (float)$this->prev_q2_3_2 + (float)$this->new_q2_3_2;
    }

    public function getEsSubtotalProperty(): float
    {
        return min($this->esRsTotal, 25);
    }

    // Professional Development
    public function getPdRsTotalProperty(): float
    {
        return (float)$this->prev_pd_subtotal + (float)$this->new_pd_subtotal;
    }

    public function getPdSubtotalProperty(): float
    {
        return min($this->pdRsTotal, 90);
    }

    // Grand Total
    public function getTotalScoreProperty(): float
    {
        return $this->eqSubtotal + $this->esSubtotal + $this->pdSubtotal;
    }

    public function mount($evaluationId = null)
    {
        $this->evaluationId = $evaluationId;

        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position.department',
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

        // ── Get or create sub-records ──
        $this->educationalQualification = $this->resolveOrCreate(
            'educational_qualification_id',
            EducationalQualification::class,
            ['q1_1' => 0, 'q1_2' => 0, 'q1_3' => 0, 'subtotal' => 0]
        );

        $this->experienceService = $this->resolveOrCreate(
            'experience_service_id',
            ExperienceService::class,
            ['q2_1_1' => 0, 'q2_1_2' => 0, 'q2_2_1' => 0, 'q2_3_1' => 0, 'q2_3_2' => 0, 'subtotal' => 0]
        );

        $this->professionalDevelopment = $this->resolveOrCreate(
            'professional_development_id',
            ProfessionalDevelopment::class,
            ['subtotal' => 0]
        );

        // ── Load previous completed assignment scores ──
        $previousAssignment = NbcAssignment::where('nbc_committee_id', $nbcCommittee->id)
            ->where('id', '!=', $this->assignment->id)
            ->where('status', 'complete')
            ->whereHas('evaluation.jobApplication', function ($q) {
                $q->where('applicant_id', $this->applicant->id);
            })
            ->where('evaluation_date', '<', $this->assignment->evaluation_date)
            ->orderByDesc('evaluation_date')
            ->first();

        if ($previousAssignment) {
            if ($previousAssignment->educational_qualification_id) {
                $prevEq = EducationalQualification::find($previousAssignment->educational_qualification_id);
                if ($prevEq) {
                    $this->prev_q1_1 = (float)($prevEq->q1_1 ?? 0);
                    $this->prev_q1_2 = (float)($prevEq->q1_2 ?? 0);
                    $this->prev_q1_3 = (float)($prevEq->q1_3 ?? 0);
                }
            }

            if ($previousAssignment->experience_service_id) {
                $prevEs = ExperienceService::find($previousAssignment->experience_service_id);
                if ($prevEs) {
                    $this->prev_q2_1_1 = (float)($prevEs->q2_1_1 ?? 0);
                    $this->prev_q2_1_2 = (float)($prevEs->q2_1_2 ?? 0);
                    $this->prev_q2_2_1 = (float)($prevEs->q2_2_1 ?? 0);
                    $this->prev_q2_3_1 = (float)($prevEs->q2_3_1 ?? 0);
                    $this->prev_q2_3_2 = (float)($prevEs->q2_3_2 ?? 0);
                }
            }

            if ($previousAssignment->professional_development_id) {
                $prevPd = ProfessionalDevelopment::find($previousAssignment->professional_development_id);
                if ($prevPd) {
                    $this->prev_pd_subtotal = (float)($prevPd->subtotal ?? 0);
                }
            }
        }

        // ── Load THIS evaluation's already-saved inputs ──
        $this->new_q1_1 = (float)($this->educationalQualification->q1_1 ?? 0);
        $this->new_q1_2 = (float)($this->educationalQualification->q1_2 ?? 0);
        $this->new_q1_3 = (float)($this->educationalQualification->q1_3 ?? 0);

        $this->new_q2_1_1 = (float)($this->experienceService->q2_1_1 ?? 0);
        $this->new_q2_1_2 = (float)($this->experienceService->q2_1_2 ?? 0);
        $this->new_q2_2_1 = (float)($this->experienceService->q2_2_1 ?? 0);
        $this->new_q2_3_1 = (float)($this->experienceService->q2_3_1 ?? 0);
        $this->new_q2_3_2 = (float)($this->experienceService->q2_3_2 ?? 0);

        $this->new_pd_subtotal = (float)($this->professionalDevelopment->subtotal ?? 0);
    }

    /**
     * Resolve existing linked record or create a new one and link it to the assignment.
     */
    protected function resolveOrCreate(string $foreignKey, string $modelClass, array $defaults)
    {
        if ($this->assignment->{$foreignKey}) {
            $record = $modelClass::find($this->assignment->{$foreignKey});
            if ($record) return $record;
        }

        $record = $modelClass::create($defaults);
        $this->assignment->update([$foreignKey => $record->id]);
        return $record;
    }

    /**
     * Persist all inputs to their respective DB records.
     */
    protected function persistCurrentInputs(): void
    {
        $this->validate([
            'new_q1_1'       => 'required|numeric|min:0',
            'new_q1_2'       => 'required|numeric|min:0',
            'new_q1_3'       => 'required|numeric|min:0|max:10',
            'new_q2_1_1'     => 'required|numeric|min:0',
            'new_q2_1_2'     => 'required|numeric|min:0',
            'new_q2_2_1'     => 'required|numeric|min:0',
            'new_q2_3_1'     => 'required|numeric|min:0',
            'new_q2_3_2'     => 'required|numeric|min:0',
            'new_pd_subtotal' => 'required|numeric|min:0|max:90',
        ]);

        $this->educationalQualification->update([
            'q1_1'     => (float)$this->new_q1_1,
            'q1_2'     => (float)$this->new_q1_2,
            'q1_3'     => (float)$this->new_q1_3,
            'subtotal' => $this->eqSubtotal,
        ]);

        $this->experienceService->update([
            'q2_1_1'   => (float)$this->new_q2_1_1,
            'q2_1_2'   => (float)$this->new_q2_1_2,
            'q2_2_1'   => (float)$this->new_q2_2_1,
            'q2_3_1'   => (float)$this->new_q2_3_1,
            'q2_3_2'   => (float)$this->new_q2_3_2,
            'subtotal' => $this->esSubtotal,
        ]);

        $this->professionalDevelopment->update([
            'subtotal' => $this->pdSubtotal,
        ]);
    }

    public function save()
    {
        $this->persistCurrentInputs();
        session()->flash('message', 'NBC scores saved successfully.');
    }

    public function submit()
    {
        $this->persistCurrentInputs();
        $this->assignment->update(['status' => 'complete']);
        session()->flash('message', 'NBC evaluation submitted successfully.');
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