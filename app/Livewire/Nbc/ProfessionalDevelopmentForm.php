<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use App\Models\NbcAssignment;
use App\Models\ProfessionalDevelopment;
use App\Models\CreativeWork;
use App\Models\Activity;
use App\Models\Recognition;
use App\Models\Award;
use App\Models\Outreach;
use App\Models\Licensure;
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
    public $existing_file_path = null;
    public $showApplicantModal  = false;

    // ── Scores from the applicant's most recent PAST completed evaluation ──

    // 3.1 Creative Works
    public $prev_q3_1_1 = 0; public $prev_q3_1_2_a = 0; public $prev_q3_1_2_c = 0;
    public $prev_q3_1_2_d = 0; public $prev_q3_1_2_e = 0; public $prev_q3_1_2_f = 0;
    public $prev_q3_1_3_a = 0; public $prev_q3_1_3_b = 0; public $prev_q3_1_3_c = 0;
    public $prev_q3_1_4 = 0;

    // 3.2.1 Training
    public $prev_q3_2_1_1_a = 0; public $prev_q3_2_1_1_b = 0; public $prev_q3_2_1_1_c = 0;
    public $prev_q3_2_1_2 = 0; public $prev_q3_2_1_3_a = 0; public $prev_q3_2_1_3_b = 0;
    public $prev_q3_2_1_3_c = 0;

    // 3.2.2 Expert Services
    public $prev_q3_2_2_1_a = 0; public $prev_q3_2_2_1_b = 0; public $prev_q3_2_2_1_c = 0;
    public $prev_q3_2_2_2_a = 0; public $prev_q3_2_2_2_b = 0; public $prev_q3_2_2_2_c = 0;
    public $prev_q3_2_2_3_a = 0; public $prev_q3_2_2_3_b = 0; public $prev_q3_2_2_3_c = 0;
    public $prev_q3_2_2_4 = 0; public $prev_q3_2_2_5 = 0; public $prev_q3_2_2_6 = 0;
    public $prev_q3_2_2_7 = 0;

    // 3.3.1 Professional Org Membership
    public $prev_q3_3_1_a_full_member = 0; public $prev_q3_3_1_a_associate_member = 0;
    public $prev_q3_3_1_b = 0; public $prev_q3_3_1_c = 0;
    public $prev_q3_3_1_d_officer = 0; public $prev_q3_3_1_d_member = 0;

    // 3.3.2 Undergraduate honors
    public $prev_q3_3_2_a = 0; public $prev_q3_3_2_b = 0; public $prev_q3_3_2_c = 0;

    // 3.3.3 Scholarship/Fellowship
    public $prev_q3_3_3_a_doctorate = 0; public $prev_q3_3_3_a_masters = 0;
    public $prev_q3_3_3_a_nondegree = 0; public $prev_q3_3_3_b_doctorate = 0;
    public $prev_q3_3_3_b_masters = 0; public $prev_q3_3_3_b_nondegree = 0;
    public $prev_q3_3_3_c_doctorate = 0; public $prev_q3_3_3_c_masters = 0;
    public $prev_q3_3_3_c_nondegree = 0; public $prev_q3_3_3_d_doctorate = 0;
    public $prev_q3_3_3_d_masters = 0; public $prev_q3_3_3_e = 0;

    // 3.4 Awards
    public $prev_q3_4_a = 0; public $prev_q3_4_b = 0; public $prev_q3_4_c = 0;

    // 3.5 Community Outreach
    public $prev_q3_3_5_1 = 0;

    // 3.6 Licensure
    public $prev_q3_6_1_a = 0; public $prev_q3_6_1_b = 0;
    public $prev_q3_6_1_c = 0; public $prev_q3_6_1_d = 0;

    // ── What this NBC member enters for THIS evaluation ──

    // 3.1 Creative Works
    public $new_q3_1_1 = 0; public $new_q3_1_2_a = 0; public $new_q3_1_2_c = 0;
    public $new_q3_1_2_d = 0; public $new_q3_1_2_e = 0; public $new_q3_1_2_f = 0;
    public $new_q3_1_3_a = 0; public $new_q3_1_3_b = 0; public $new_q3_1_3_c = 0;
    public $new_q3_1_4 = 0;

    // 3.2.1 Training
    public $new_q3_2_1_1_a = 0; public $new_q3_2_1_1_b = 0; public $new_q3_2_1_1_c = 0;
    public $new_q3_2_1_2 = 0; public $new_q3_2_1_3_a = 0; public $new_q3_2_1_3_b = 0;
    public $new_q3_2_1_3_c = 0;

    // 3.2.2 Expert Services
    public $new_q3_2_2_1_a = 0; public $new_q3_2_2_1_b = 0; public $new_q3_2_2_1_c = 0;
    public $new_q3_2_2_2_a = 0; public $new_q3_2_2_2_b = 0; public $new_q3_2_2_2_c = 0;
    public $new_q3_2_2_3_a = 0; public $new_q3_2_2_3_b = 0; public $new_q3_2_2_3_c = 0;
    public $new_q3_2_2_4 = 0; public $new_q3_2_2_5 = 0; public $new_q3_2_2_6 = 0;
    public $new_q3_2_2_7 = 0;

    // 3.3.1 Professional Org Membership
    public $new_q3_3_1_a_full_member = 0; public $new_q3_3_1_a_associate_member = 0;
    public $new_q3_3_1_b = 0; public $new_q3_3_1_c = 0;
    public $new_q3_3_1_d_officer = 0; public $new_q3_3_1_d_member = 0;

    // 3.3.2 Undergraduate honors
    public $new_q3_3_2_a = 0; public $new_q3_3_2_b = 0; public $new_q3_3_2_c = 0;

    // 3.3.3 Scholarship/Fellowship
    public $new_q3_3_3_a_doctorate = 0; public $new_q3_3_3_a_masters = 0;
    public $new_q3_3_3_a_nondegree = 0; public $new_q3_3_3_b_doctorate = 0;
    public $new_q3_3_3_b_masters = 0; public $new_q3_3_3_b_nondegree = 0;
    public $new_q3_3_3_c_doctorate = 0; public $new_q3_3_3_c_masters = 0;
    public $new_q3_3_3_c_nondegree = 0; public $new_q3_3_3_d_doctorate = 0;
    public $new_q3_3_3_d_masters = 0; public $new_q3_3_3_e = 0;

    // 3.4 Awards
    public $new_q3_4_a = 0; public $new_q3_4_b = 0; public $new_q3_4_c = 0;

    // 3.5 Community Outreach
    public $new_q3_3_5_1 = 0;

    // 3.6 Licensure
    public $new_q3_6_1_a = 0; public $new_q3_6_1_b = 0;
    public $new_q3_6_1_c = 0; public $new_q3_6_1_d = 0;

    // ─── Helper: sum a list of $this property names as floats ────────────────

    private function sumFields(array $fields): float
    {
        $total = 0.0;
        foreach ($fields as $field) {
            $total += (float)$this->{$field};
        }
        return $total;
    }

    // ─── Previous-only raw subtotals (uncapped) ───────────────────────────────

    public function getPrevSubtotal31Property(): float
    {
        return $this->sumFields([
            'prev_q3_1_1','prev_q3_1_2_a','prev_q3_1_2_c','prev_q3_1_2_d',
            'prev_q3_1_2_e','prev_q3_1_2_f','prev_q3_1_3_a','prev_q3_1_3_b',
            'prev_q3_1_3_c','prev_q3_1_4',
        ]);
    }

    public function getPrevSubtotal321Property(): float
    {
        return $this->sumFields([
            'prev_q3_2_1_1_a','prev_q3_2_1_1_b','prev_q3_2_1_1_c',
            'prev_q3_2_1_2',
            'prev_q3_2_1_3_a','prev_q3_2_1_3_b','prev_q3_2_1_3_c',
        ]);
    }

    public function getPrevSubtotal322Property(): float
    {
        return $this->sumFields([
            'prev_q3_2_2_1_a','prev_q3_2_2_1_b','prev_q3_2_2_1_c',
            'prev_q3_2_2_2_a','prev_q3_2_2_2_b','prev_q3_2_2_2_c',
            'prev_q3_2_2_3_a','prev_q3_2_2_3_b','prev_q3_2_2_3_c',
            'prev_q3_2_2_4','prev_q3_2_2_5','prev_q3_2_2_6','prev_q3_2_2_7',
        ]);
    }

    public function getPrevSubtotal33Property(): float
    {
        return $this->sumFields([
            'prev_q3_3_1_a_full_member','prev_q3_3_1_a_associate_member',
            'prev_q3_3_1_b','prev_q3_3_1_c',
            'prev_q3_3_1_d_officer','prev_q3_3_1_d_member',
            'prev_q3_3_2_a','prev_q3_3_2_b','prev_q3_3_2_c',
            'prev_q3_3_3_a_doctorate','prev_q3_3_3_a_masters','prev_q3_3_3_a_nondegree',
            'prev_q3_3_3_b_doctorate','prev_q3_3_3_b_masters','prev_q3_3_3_b_nondegree',
            'prev_q3_3_3_c_doctorate','prev_q3_3_3_c_masters','prev_q3_3_3_c_nondegree',
            'prev_q3_3_3_d_doctorate','prev_q3_3_3_d_masters','prev_q3_3_3_e',
        ]);
    }

    public function getPrevSubtotal34Property(): float
    {
        return $this->sumFields(['prev_q3_4_a','prev_q3_4_b','prev_q3_4_c']);
    }

    public function getPrevSubtotal35Property(): float
    {
        return (float)$this->prev_q3_3_5_1;
    }

    public function getPrevSubtotal36Property(): float
    {
        return $this->sumFields([
            'prev_q3_6_1_a','prev_q3_6_1_b','prev_q3_6_1_c','prev_q3_6_1_d',
        ]);
    }

    // ─── New-only raw subtotals (uncapped) ───────────────────────────────────

    public function getNewSubtotal31Property(): float
    {
        return $this->sumFields([
            'new_q3_1_1','new_q3_1_2_a','new_q3_1_2_c','new_q3_1_2_d',
            'new_q3_1_2_e','new_q3_1_2_f','new_q3_1_3_a','new_q3_1_3_b',
            'new_q3_1_3_c','new_q3_1_4',
        ]);
    }

    public function getNewSubtotal321Property(): float
    {
        return $this->sumFields([
            'new_q3_2_1_1_a','new_q3_2_1_1_b','new_q3_2_1_1_c',
            'new_q3_2_1_2',
            'new_q3_2_1_3_a','new_q3_2_1_3_b','new_q3_2_1_3_c',
        ]);
    }

    public function getNewSubtotal322Property(): float
    {
        return $this->sumFields([
            'new_q3_2_2_1_a','new_q3_2_2_1_b','new_q3_2_2_1_c',
            'new_q3_2_2_2_a','new_q3_2_2_2_b','new_q3_2_2_2_c',
            'new_q3_2_2_3_a','new_q3_2_2_3_b','new_q3_2_2_3_c',
            'new_q3_2_2_4','new_q3_2_2_5','new_q3_2_2_6','new_q3_2_2_7',
        ]);
    }

    public function getNewSubtotal33Property(): float
    {
        return $this->sumFields([
            'new_q3_3_1_a_full_member','new_q3_3_1_a_associate_member',
            'new_q3_3_1_b','new_q3_3_1_c',
            'new_q3_3_1_d_officer','new_q3_3_1_d_member',
            'new_q3_3_2_a','new_q3_3_2_b','new_q3_3_2_c',
            'new_q3_3_3_a_doctorate','new_q3_3_3_a_masters','new_q3_3_3_a_nondegree',
            'new_q3_3_3_b_doctorate','new_q3_3_3_b_masters','new_q3_3_3_b_nondegree',
            'new_q3_3_3_c_doctorate','new_q3_3_3_c_masters','new_q3_3_3_c_nondegree',
            'new_q3_3_3_d_doctorate','new_q3_3_3_d_masters','new_q3_3_3_e',
        ]);
    }

    public function getNewSubtotal34Property(): float
    {
        return $this->sumFields(['new_q3_4_a','new_q3_4_b','new_q3_4_c']);
    }

    public function getNewSubtotal35Property(): float
    {
        return (float)$this->new_q3_3_5_1;
    }

    public function getNewSubtotal36Property(): float
    {
        return $this->sumFields([
            'new_q3_6_1_a','new_q3_6_1_b','new_q3_6_1_c','new_q3_6_1_d',
        ]);
    }

    // ─── Capped combined subtotals: prev + new ────────────────────────────────

    // 3.1 cap = 30
    public function getSubtotal31Property(): float
    {
        return min($this->prevSubtotal31 + $this->newSubtotal31, 30.0);
    }

    // 3.2.1 cap = 10
    public function getSubtotal321Property(): float
    {
        return min($this->prevSubtotal321 + $this->newSubtotal321, 10.0);
    }

    // 3.2.2 cap = 20
    public function getSubtotal322Property(): float
    {
        return min($this->prevSubtotal322 + $this->newSubtotal322, 20.0);
    }

    // 3.3 cap = 10
    public function getSubtotal33Property(): float
    {
        return min($this->prevSubtotal33 + $this->newSubtotal33, 10.0);
    }

    // 3.4 cap = 5
    public function getSubtotal34Property(): float
    {
        return min($this->prevSubtotal34 + $this->newSubtotal34, 5.0);
    }

    // 3.5 cap = 5
    public function getSubtotal35Property(): float
    {
        return min($this->prevSubtotal35 + $this->newSubtotal35, 5.0);
    }

    // 3.6 cap = 10
    public function getSubtotal36Property(): float
    {
        return min($this->prevSubtotal36 + $this->newSubtotal36, 10.0);
    }

    // ─── Page running totals ──────────────────────────────────────────────────

    // Page 1: only section 3.1 (cap 30)
    public function getPage1TotalProperty(): float
    {
        return $this->subtotal31;
    }

    // Page 2: sections 3.1 + 3.2.1 + 3.2.2 (cap 60)
    public function getPage2TotalProperty(): float
    {
        return min(
            $this->subtotal31 + $this->subtotal321 + $this->subtotal322,
            60.0
        );
    }

    // Page 3: all sections (cap 90)
    public function getPage3TotalProperty(): float
    {
        return min(
            $this->subtotal31  + $this->subtotal321 + $this->subtotal322
          + $this->subtotal33  + $this->subtotal34  + $this->subtotal35
          + $this->subtotal36,
            90.0
        );
    }

    // Previous grand total (cap 90)
    public function getPrevGrandTotalProperty(): float
    {
        return min(
            $this->prevSubtotal31  + $this->prevSubtotal321 + $this->prevSubtotal322
          + $this->prevSubtotal33  + $this->prevSubtotal34  + $this->prevSubtotal35
          + $this->prevSubtotal36,
            90.0
        );
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(int $evaluationId)
    {
        $this->evaluationId = $evaluationId;

        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])->findOrFail($evaluationId);

        $this->jobApplication     = $this->evaluation->jobApplication;
        $this->applicant          = $this->jobApplication->applicant;
        $this->position           = $this->jobApplication->position;
        $this->existing_file_path = $this->jobApplication->requirements_file;

        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->firstOrFail();

        $this->assignment = NbcAssignment::firstOrCreate(
            ['nbc_committee_id' => $nbcCommittee->id, 'evaluation_id' => $this->evaluation->id],
            ['status' => 'pending', 'evaluation_date' => now()]
        );

        if ($this->assignment->professional_development_id) {
            $this->professionalDevelopment = ProfessionalDevelopment::with([
                'creativeWork','activity','recognition','award','outreach','licensure',
            ])->find($this->assignment->professional_development_id);
        }

        if (!isset($this->professionalDevelopment) || !$this->professionalDevelopment) {
            $this->professionalDevelopment = ProfessionalDevelopment::create(['subtotal' => 0]);
            $this->assignment->update(['professional_development_id' => $this->professionalDevelopment->id]);
        } else {
            $this->professionalDevelopment->load(['creativeWork','activity','recognition','award','outreach','licensure']);
        }

        $this->ensureSubModels();

        $previousAssignment = NbcAssignment::where('nbc_committee_id', $nbcCommittee->id)
            ->where('id', '!=', $this->assignment->id)
            ->where('status', 'complete')
            ->whereHas('evaluation.jobApplication', function ($q) {
                $q->where('applicant_id', $this->applicant->id);
            })
            ->where('evaluation_date', '<', $this->assignment->evaluation_date)
            ->orderByDesc('evaluation_date')
            ->first();

        if ($previousAssignment && $previousAssignment->professional_development_id) {
            $prevPd = ProfessionalDevelopment::with([
                'creativeWork','activity','recognition','award','outreach','licensure',
            ])->find($previousAssignment->professional_development_id);
            if ($prevPd) {
                $this->loadPrevFromModel($prevPd);
            }
        }

        $this->loadCurrentIntoNew();
    }

    protected function ensureSubModels(): void
    {
        $pd = $this->professionalDevelopment;

        if (!$pd->creative_work_id) {
            $cw = CreativeWork::create([
                'q3_1_1'=>0,'q3_1_2_a'=>0,'q3_1_2_c'=>0,'q3_1_2_d'=>0,
                'q3_1_2_e'=>0,'q3_1_2_f'=>0,'q3_1_3_a'=>0,'q3_1_3_b'=>0,
                'q3_1_3_c'=>0,'q3_1_4'=>0,'subtotal'=>0,
            ]);
            $pd->update(['creative_work_id' => $cw->id]);
        }
        if (!$pd->activity_id) {
            $act = Activity::create([
                'q3_2_1_1_a'=>0,'q3_2_1_1_b'=>0,'q3_2_1_1_c'=>0,'q3_2_1_2'=>0,
                'q3_2_1_3_a'=>0,'q3_2_1_3_b'=>0,'q3_2_1_3_c'=>0,
                'q3_2_2_1_a'=>0,'q3_2_2_1_b'=>0,'q3_2_2_1_c'=>0,
                'q3_2_2_2_a'=>0,'q3_2_2_2_b'=>0,'q3_2_2_2_c'=>0,
                'q3_2_2_3_a'=>0,'q3_2_2_3_b'=>0,'q3_2_2_3_c'=>0,
                'q3_2_2_4'=>0,'q3_2_2_5'=>0,'q3_2_2_6'=>0,'q3_2_2_7'=>0,
                'subtotal_321'=>0,'subtotal_322'=>0,'subtotal'=>0,
            ]);
            $pd->update(['activity_id' => $act->id]);
        }
        if (!$pd->recognition_id) {
            $rec = Recognition::create([
                'q3_3_1_a_full_member'=>0,'q3_3_1_a_associate_member'=>0,
                'q3_3_1_b'=>0,'q3_3_1_c'=>0,
                'q3_3_1_d_officer'=>0,'q3_3_1_d_member'=>0,
                'q3_3_2_a'=>0,'q3_3_2_b'=>0,'q3_3_2_c'=>0,
                'q3_3_3_a_doctorate'=>0,'q3_3_3_a_masters'=>0,'q3_3_3_a_nondegree'=>0,
                'q3_3_3_b_doctorate'=>0,'q3_3_3_b_masters'=>0,'q3_3_3_b_nondegree'=>0,
                'q3_3_3_c_doctorate'=>0,'q3_3_3_c_masters'=>0,'q3_3_3_c_nondegree'=>0,
                'q3_3_3_d_doctorate'=>0,'q3_3_3_d_masters'=>0,'q3_3_3_e'=>0,
                'subtotal'=>0,
            ]);
            $pd->update(['recognition_id' => $rec->id]);
        }
        if (!$pd->award_id) {
            $aw = Award::create(['q3_4_a'=>0,'q3_4_b'=>0,'q3_4_c'=>0,'subtotal'=>0]);
            $pd->update(['award_id' => $aw->id]);
        }
        if (!$pd->outreach_id) {
            $out = Outreach::create(['q3_3_5_1'=>0,'subtotal'=>0]);
            $pd->update(['outreach_id' => $out->id]);
        }
        if (!$pd->licensure_id) {
            $lic = Licensure::create(['q3_6_1_a'=>0,'q3_6_1_b'=>0,'q3_6_1_c'=>0,'q3_6_1_d'=>0,'subtotal'=>0]);
            $pd->update(['licensure_id' => $lic->id]);
        }

        $this->professionalDevelopment->refresh();
        $this->professionalDevelopment->load(['creativeWork','activity','recognition','award','outreach','licensure']);
    }

    protected function loadPrevFromModel(ProfessionalDevelopment $pd): void
    {
        $cw  = $pd->creativeWork;
        $act = $pd->activity;
        $rec = $pd->recognition;
        $aw  = $pd->award;
        $out = $pd->outreach;
        $lic = $pd->licensure;

        if ($cw) {
            $this->prev_q3_1_1   = (float)($cw->q3_1_1   ?? 0);
            $this->prev_q3_1_2_a = (float)($cw->q3_1_2_a ?? 0);
            $this->prev_q3_1_2_c = (float)($cw->q3_1_2_c ?? 0);
            $this->prev_q3_1_2_d = (float)($cw->q3_1_2_d ?? 0);
            $this->prev_q3_1_2_e = (float)($cw->q3_1_2_e ?? 0);
            $this->prev_q3_1_2_f = (float)($cw->q3_1_2_f ?? 0);
            $this->prev_q3_1_3_a = (float)($cw->q3_1_3_a ?? 0);
            $this->prev_q3_1_3_b = (float)($cw->q3_1_3_b ?? 0);
            $this->prev_q3_1_3_c = (float)($cw->q3_1_3_c ?? 0);
            $this->prev_q3_1_4   = (float)($cw->q3_1_4   ?? 0);
        }
        if ($act) {
            $this->prev_q3_2_1_1_a = (float)($act->q3_2_1_1_a ?? 0);
            $this->prev_q3_2_1_1_b = (float)($act->q3_2_1_1_b ?? 0);
            $this->prev_q3_2_1_1_c = (float)($act->q3_2_1_1_c ?? 0);
            $this->prev_q3_2_1_2   = (float)($act->q3_2_1_2   ?? 0);
            $this->prev_q3_2_1_3_a = (float)($act->q3_2_1_3_a ?? 0);
            $this->prev_q3_2_1_3_b = (float)($act->q3_2_1_3_b ?? 0);
            $this->prev_q3_2_1_3_c = (float)($act->q3_2_1_3_c ?? 0);
            $this->prev_q3_2_2_1_a = (float)($act->q3_2_2_1_a ?? 0);
            $this->prev_q3_2_2_1_b = (float)($act->q3_2_2_1_b ?? 0);
            $this->prev_q3_2_2_1_c = (float)($act->q3_2_2_1_c ?? 0);
            $this->prev_q3_2_2_2_a = (float)($act->q3_2_2_2_a ?? 0);
            $this->prev_q3_2_2_2_b = (float)($act->q3_2_2_2_b ?? 0);
            $this->prev_q3_2_2_2_c = (float)($act->q3_2_2_2_c ?? 0);
            $this->prev_q3_2_2_3_a = (float)($act->q3_2_2_3_a ?? 0);
            $this->prev_q3_2_2_3_b = (float)($act->q3_2_2_3_b ?? 0);
            $this->prev_q3_2_2_3_c = (float)($act->q3_2_2_3_c ?? 0);
            $this->prev_q3_2_2_4   = (float)($act->q3_2_2_4   ?? 0);
            $this->prev_q3_2_2_5   = (float)($act->q3_2_2_5   ?? 0);
            $this->prev_q3_2_2_6   = (float)($act->q3_2_2_6   ?? 0);
            $this->prev_q3_2_2_7   = (float)($act->q3_2_2_7   ?? 0);
        }
        if ($rec) {
            $this->prev_q3_3_1_a_full_member      = (float)($rec->q3_3_1_a_full_member      ?? 0);
            $this->prev_q3_3_1_a_associate_member  = (float)($rec->q3_3_1_a_associate_member  ?? 0);
            $this->prev_q3_3_1_b                  = (float)($rec->q3_3_1_b                  ?? 0);
            $this->prev_q3_3_1_c                  = (float)($rec->q3_3_1_c                  ?? 0);
            $this->prev_q3_3_1_d_officer          = (float)($rec->q3_3_1_d_officer          ?? 0);
            $this->prev_q3_3_1_d_member           = (float)($rec->q3_3_1_d_member           ?? 0);
            $this->prev_q3_3_2_a                  = (float)($rec->q3_3_2_a                  ?? 0);
            $this->prev_q3_3_2_b                  = (float)($rec->q3_3_2_b                  ?? 0);
            $this->prev_q3_3_2_c                  = (float)($rec->q3_3_2_c                  ?? 0);
            $this->prev_q3_3_3_a_doctorate        = (float)($rec->q3_3_3_a_doctorate        ?? 0);
            $this->prev_q3_3_3_a_masters          = (float)($rec->q3_3_3_a_masters          ?? 0);
            $this->prev_q3_3_3_a_nondegree        = (float)($rec->q3_3_3_a_nondegree        ?? 0);
            $this->prev_q3_3_3_b_doctorate        = (float)($rec->q3_3_3_b_doctorate        ?? 0);
            $this->prev_q3_3_3_b_masters          = (float)($rec->q3_3_3_b_masters          ?? 0);
            $this->prev_q3_3_3_b_nondegree        = (float)($rec->q3_3_3_b_nondegree        ?? 0);
            $this->prev_q3_3_3_c_doctorate        = (float)($rec->q3_3_3_c_doctorate        ?? 0);
            $this->prev_q3_3_3_c_masters          = (float)($rec->q3_3_3_c_masters          ?? 0);
            $this->prev_q3_3_3_c_nondegree        = (float)($rec->q3_3_3_c_nondegree        ?? 0);
            $this->prev_q3_3_3_d_doctorate        = (float)($rec->q3_3_3_d_doctorate        ?? 0);
            $this->prev_q3_3_3_d_masters          = (float)($rec->q3_3_3_d_masters          ?? 0);
            $this->prev_q3_3_3_e                  = (float)($rec->q3_3_3_e                  ?? 0);
        }
        if ($aw) {
            $this->prev_q3_4_a = (float)($aw->q3_4_a ?? 0);
            $this->prev_q3_4_b = (float)($aw->q3_4_b ?? 0);
            $this->prev_q3_4_c = (float)($aw->q3_4_c ?? 0);
        }
        if ($out) {
            $this->prev_q3_3_5_1 = (float)($out->q3_3_5_1 ?? 0);
        }
        if ($lic) {
            $this->prev_q3_6_1_a = (float)($lic->q3_6_1_a ?? 0);
            $this->prev_q3_6_1_b = (float)($lic->q3_6_1_b ?? 0);
            $this->prev_q3_6_1_c = (float)($lic->q3_6_1_c ?? 0);
            $this->prev_q3_6_1_d = (float)($lic->q3_6_1_d ?? 0);
        }
    }

    protected function loadCurrentIntoNew(): void
    {
        $pd  = $this->professionalDevelopment;
        $cw  = $pd->creativeWork;
        $act = $pd->activity;
        $rec = $pd->recognition;
        $aw  = $pd->award;
        $out = $pd->outreach;
        $lic = $pd->licensure;

        if ($cw) {
            $this->new_q3_1_1   = (float)($cw->q3_1_1   ?? 0);
            $this->new_q3_1_2_a = (float)($cw->q3_1_2_a ?? 0);
            $this->new_q3_1_2_c = (float)($cw->q3_1_2_c ?? 0);
            $this->new_q3_1_2_d = (float)($cw->q3_1_2_d ?? 0);
            $this->new_q3_1_2_e = (float)($cw->q3_1_2_e ?? 0);
            $this->new_q3_1_2_f = (float)($cw->q3_1_2_f ?? 0);
            $this->new_q3_1_3_a = (float)($cw->q3_1_3_a ?? 0);
            $this->new_q3_1_3_b = (float)($cw->q3_1_3_b ?? 0);
            $this->new_q3_1_3_c = (float)($cw->q3_1_3_c ?? 0);
            $this->new_q3_1_4   = (float)($cw->q3_1_4   ?? 0);
        }
        if ($act) {
            $this->new_q3_2_1_1_a = (float)($act->q3_2_1_1_a ?? 0);
            $this->new_q3_2_1_1_b = (float)($act->q3_2_1_1_b ?? 0);
            $this->new_q3_2_1_1_c = (float)($act->q3_2_1_1_c ?? 0);
            $this->new_q3_2_1_2   = (float)($act->q3_2_1_2   ?? 0);
            $this->new_q3_2_1_3_a = (float)($act->q3_2_1_3_a ?? 0);
            $this->new_q3_2_1_3_b = (float)($act->q3_2_1_3_b ?? 0);
            $this->new_q3_2_1_3_c = (float)($act->q3_2_1_3_c ?? 0);
            $this->new_q3_2_2_1_a = (float)($act->q3_2_2_1_a ?? 0);
            $this->new_q3_2_2_1_b = (float)($act->q3_2_2_1_b ?? 0);
            $this->new_q3_2_2_1_c = (float)($act->q3_2_2_1_c ?? 0);
            $this->new_q3_2_2_2_a = (float)($act->q3_2_2_2_a ?? 0);
            $this->new_q3_2_2_2_b = (float)($act->q3_2_2_2_b ?? 0);
            $this->new_q3_2_2_2_c = (float)($act->q3_2_2_2_c ?? 0);
            $this->new_q3_2_2_3_a = (float)($act->q3_2_2_3_a ?? 0);
            $this->new_q3_2_2_3_b = (float)($act->q3_2_2_3_b ?? 0);
            $this->new_q3_2_2_3_c = (float)($act->q3_2_2_3_c ?? 0);
            $this->new_q3_2_2_4   = (float)($act->q3_2_2_4   ?? 0);
            $this->new_q3_2_2_5   = (float)($act->q3_2_2_5   ?? 0);
            $this->new_q3_2_2_6   = (float)($act->q3_2_2_6   ?? 0);
            $this->new_q3_2_2_7   = (float)($act->q3_2_2_7   ?? 0);
        }
        if ($rec) {
            $this->new_q3_3_1_a_full_member      = (float)($rec->q3_3_1_a_full_member      ?? 0);
            $this->new_q3_3_1_a_associate_member  = (float)($rec->q3_3_1_a_associate_member  ?? 0);
            $this->new_q3_3_1_b                  = (float)($rec->q3_3_1_b                  ?? 0);
            $this->new_q3_3_1_c                  = (float)($rec->q3_3_1_c                  ?? 0);
            $this->new_q3_3_1_d_officer          = (float)($rec->q3_3_1_d_officer          ?? 0);
            $this->new_q3_3_1_d_member           = (float)($rec->q3_3_1_d_member           ?? 0);
            $this->new_q3_3_2_a                  = (float)($rec->q3_3_2_a                  ?? 0);
            $this->new_q3_3_2_b                  = (float)($rec->q3_3_2_b                  ?? 0);
            $this->new_q3_3_2_c                  = (float)($rec->q3_3_2_c                  ?? 0);
            $this->new_q3_3_3_a_doctorate        = (float)($rec->q3_3_3_a_doctorate        ?? 0);
            $this->new_q3_3_3_a_masters          = (float)($rec->q3_3_3_a_masters          ?? 0);
            $this->new_q3_3_3_a_nondegree        = (float)($rec->q3_3_3_a_nondegree        ?? 0);
            $this->new_q3_3_3_b_doctorate        = (float)($rec->q3_3_3_b_doctorate        ?? 0);
            $this->new_q3_3_3_b_masters          = (float)($rec->q3_3_3_b_masters          ?? 0);
            $this->new_q3_3_3_b_nondegree        = (float)($rec->q3_3_3_b_nondegree        ?? 0);
            $this->new_q3_3_3_c_doctorate        = (float)($rec->q3_3_3_c_doctorate        ?? 0);
            $this->new_q3_3_3_c_masters          = (float)($rec->q3_3_3_c_masters          ?? 0);
            $this->new_q3_3_3_c_nondegree        = (float)($rec->q3_3_3_c_nondegree        ?? 0);
            $this->new_q3_3_3_d_doctorate        = (float)($rec->q3_3_3_d_doctorate        ?? 0);
            $this->new_q3_3_3_d_masters          = (float)($rec->q3_3_3_d_masters          ?? 0);
            $this->new_q3_3_3_e                  = (float)($rec->q3_3_3_e                  ?? 0);
        }
        if ($aw) {
            $this->new_q3_4_a = (float)($aw->q3_4_a ?? 0);
            $this->new_q3_4_b = (float)($aw->q3_4_b ?? 0);
            $this->new_q3_4_c = (float)($aw->q3_4_c ?? 0);
        }
        if ($out) {
            $this->new_q3_3_5_1 = (float)($out->q3_3_5_1 ?? 0);
        }
        if ($lic) {
            $this->new_q3_6_1_a = (float)($lic->q3_6_1_a ?? 0);
            $this->new_q3_6_1_b = (float)($lic->q3_6_1_b ?? 0);
            $this->new_q3_6_1_c = (float)($lic->q3_6_1_c ?? 0);
            $this->new_q3_6_1_d = (float)($lic->q3_6_1_d ?? 0);
        }
    }

    protected function persistCurrentInputs(): void
    {
        $pd = $this->professionalDevelopment;

        // ── 3.1 Creative Works (cap 30) ──────────────────────────────────────
        $cw_fields = [
            'q3_1_1','q3_1_2_a','q3_1_2_c','q3_1_2_d',
            'q3_1_2_e','q3_1_2_f','q3_1_3_a','q3_1_3_b',
            'q3_1_3_c','q3_1_4',
        ];
        $cwData = [];
        foreach ($cw_fields as $f) {
            $cwData[$f] = (float)$this->{'new_'.$f};
        }
        $cwData['subtotal'] = min(array_sum($cwData), 30.0);
        $pd->creativeWork->update($cwData);

        // ── 3.2.1 Training fields (cap 10) ───────────────────────────────────
        $act321_fields = [
            'q3_2_1_1_a','q3_2_1_1_b','q3_2_1_1_c',
            'q3_2_1_2',
            'q3_2_1_3_a','q3_2_1_3_b','q3_2_1_3_c',
        ];
        $act321Data = [];
        foreach ($act321_fields as $f) {
            $act321Data[$f] = (float)$this->{'new_'.$f};
        }
        $subtotal321 = min(array_sum($act321Data), 10.0);

        // ── 3.2.2 Expert Services fields (cap 20) ─────────────────────────────
        $act322_fields = [
            'q3_2_2_1_a','q3_2_2_1_b','q3_2_2_1_c',
            'q3_2_2_2_a','q3_2_2_2_b','q3_2_2_2_c',
            'q3_2_2_3_a','q3_2_2_3_b','q3_2_2_3_c',
            'q3_2_2_4','q3_2_2_5','q3_2_2_6','q3_2_2_7',
        ];
        $act322Data = [];
        foreach ($act322_fields as $f) {
            $act322Data[$f] = (float)$this->{'new_'.$f};
        }
        $subtotal322 = min(array_sum($act322Data), 20.0);

        // Persist activity with separate subtotals stored and combined total
        $actData = array_merge($act321Data, $act322Data, [
            'subtotal_321' => $subtotal321,
            'subtotal_322' => $subtotal322,
            'subtotal'     => min($subtotal321 + $subtotal322, 30.0),
        ]);
        $pd->activity->update($actData);

        // ── 3.3 Recognition (cap 10) ──────────────────────────────────────────
        $rec_fields = [
            'q3_3_1_a_full_member','q3_3_1_a_associate_member',
            'q3_3_1_b','q3_3_1_c','q3_3_1_d_officer','q3_3_1_d_member',
            'q3_3_2_a','q3_3_2_b','q3_3_2_c',
            'q3_3_3_a_doctorate','q3_3_3_a_masters','q3_3_3_a_nondegree',
            'q3_3_3_b_doctorate','q3_3_3_b_masters','q3_3_3_b_nondegree',
            'q3_3_3_c_doctorate','q3_3_3_c_masters','q3_3_3_c_nondegree',
            'q3_3_3_d_doctorate','q3_3_3_d_masters','q3_3_3_e',
        ];
        $recData = [];
        foreach ($rec_fields as $f) {
            $recData[$f] = (float)$this->{'new_'.$f};
        }
        $recData['subtotal'] = min(array_sum($recData), 10.0);
        $pd->recognition->update($recData);

        // ── 3.4 Awards (cap 5) ────────────────────────────────────────────────
        $awData = [
            'q3_4_a' => (float)$this->new_q3_4_a,
            'q3_4_b' => (float)$this->new_q3_4_b,
            'q3_4_c' => (float)$this->new_q3_4_c,
        ];
        $awData['subtotal'] = min(array_sum($awData), 5.0);
        $pd->award->update($awData);

        // ── 3.5 Outreach (cap 5) ──────────────────────────────────────────────
        $outVal = (float)$this->new_q3_3_5_1;
        $pd->outreach->update([
            'q3_3_5_1' => $outVal,
            'subtotal'  => min($outVal, 5.0),
        ]);

        // ── 3.6 Licensure (cap 10) ────────────────────────────────────────────
        $licData = [
            'q3_6_1_a' => (float)$this->new_q3_6_1_a,
            'q3_6_1_b' => (float)$this->new_q3_6_1_b,
            'q3_6_1_c' => (float)$this->new_q3_6_1_c,
            'q3_6_1_d' => (float)$this->new_q3_6_1_d,
        ];
        $licData['subtotal'] = min(array_sum($licData), 10.0);
        $pd->licensure->update($licData);
    }

    protected function finalizeAndSave(): void
    {
        $this->persistCurrentInputs();
        $this->professionalDevelopment->update(['subtotal' => $this->page3Total]);
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
        if ($this->currentPage > 1) {
            $this->persistCurrentInputs();
            $this->currentPage--;
        } else {
            $this->persistCurrentInputs();
            return redirect()->route('nbc.experience-service', ['evaluationId' => $this->evaluation->id]);
        }
    }

    public function next()
    {
        if ($this->currentPage < 3) {
            $this->persistCurrentInputs();
            $this->currentPage++;
        }
    }

    public function submit()
    {
        $this->finalizeAndSave();
        $this->assignment->update(['status' => 'complete']);
        session()->flash('message', 'Professional development evaluation completed successfully.');
        return redirect()->route('nbc.dashboard');
    }

    public function render()
    {
        return view('livewire.nbc.professional-development-form');
    }
}