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
    // Read-only. Comes from a different (older) nbc_assignment
    // for the SAME APPLICANT (any previous job application / position).
    public $prev_q3_1_1 = 0; public $prev_q3_1_2_a = 0; public $prev_q3_1_2_c = 0;
    public $prev_q3_1_2_d = 0; public $prev_q3_1_2_e = 0; public $prev_q3_1_2_f = 0;
    public $prev_q3_1_3_a = 0; public $prev_q3_1_3_b = 0; public $prev_q3_1_3_c = 0;
    public $prev_q3_1_4 = 0;
    public $prev_q3_2_1_1_a = 0; public $prev_q3_2_1_1_b = 0; public $prev_q3_2_1_1_c = 0;
    public $prev_q3_2_1_2 = 0; public $prev_q3_2_1_3_a = 0; public $prev_q3_2_1_3_b = 0;
    public $prev_q3_2_1_3_c = 0; public $prev_q3_2_2_1_a = 0; public $prev_q3_2_2_1_b = 0;
    public $prev_q3_2_2_1_c = 0; public $prev_q3_2_2_2 = 0; public $prev_q3_2_2_3 = 0;
    public $prev_q3_2_2_4 = 0; public $prev_q3_2_2_5 = 0; public $prev_q3_2_2_6 = 0;
    public $prev_q3_2_2_7 = 0;
    public $prev_q3_3_1_a = 0; public $prev_q3_3_1_b = 0; public $prev_q3_3_1_c = 0;
    public $prev_q3_3_2 = 0; public $prev_q3_3_3_a_doctorate = 0;
    public $prev_q3_3_3_a_masters = 0; public $prev_q3_3_3_a_nondegree = 0;
    public $prev_q3_3_3_b_doctorate = 0; public $prev_q3_3_3_b_masters = 0;
    public $prev_q3_3_3_b_nondegree = 0; public $prev_q3_3_3_c_doctorate = 0;
    public $prev_q3_3_3_c_masters = 0; public $prev_q3_3_3_c_nondegree = 0;
    public $prev_q3_3_3_d_doctorate = 0; public $prev_q3_3_3_d_masters = 0;
    public $prev_q3_3_3_e = 0;
    public $prev_q3_4_a = 0; public $prev_q3_4_b = 0; public $prev_q3_4_c = 0;
    public $prev_q3_3_5_1 = 0;
    public $prev_q3_6_1_a = 0; public $prev_q3_6_1_b = 0;
    public $prev_q3_6_1_c = 0; public $prev_q3_6_1_d = 0;

    // ── What this NBC member enters for THIS evaluation ──
    // Saved to DB on page navigation so switching pages retains inputs.
    // Only combined with prev_ on final Submit.
    public $new_q3_1_1 = 0; public $new_q3_1_2_a = 0; public $new_q3_1_2_c = 0;
    public $new_q3_1_2_d = 0; public $new_q3_1_2_e = 0; public $new_q3_1_2_f = 0;
    public $new_q3_1_3_a = 0; public $new_q3_1_3_b = 0; public $new_q3_1_3_c = 0;
    public $new_q3_1_4 = 0;
    public $new_q3_2_1_1_a = 0; public $new_q3_2_1_1_b = 0; public $new_q3_2_1_1_c = 0;
    public $new_q3_2_1_2 = 0; public $new_q3_2_1_3_a = 0; public $new_q3_2_1_3_b = 0;
    public $new_q3_2_1_3_c = 0; public $new_q3_2_2_1_a = 0; public $new_q3_2_2_1_b = 0;
    public $new_q3_2_2_1_c = 0; public $new_q3_2_2_2 = 0; public $new_q3_2_2_3 = 0;
    public $new_q3_2_2_4 = 0; public $new_q3_2_2_5 = 0; public $new_q3_2_2_6 = 0;
    public $new_q3_2_2_7 = 0;
    public $new_q3_3_1_a = 0; public $new_q3_3_1_b = 0; public $new_q3_3_1_c = 0;
    public $new_q3_3_2 = 0; public $new_q3_3_3_a_doctorate = 0;
    public $new_q3_3_3_a_masters = 0; public $new_q3_3_3_a_nondegree = 0;
    public $new_q3_3_3_b_doctorate = 0; public $new_q3_3_3_b_masters = 0;
    public $new_q3_3_3_b_nondegree = 0; public $new_q3_3_3_c_doctorate = 0;
    public $new_q3_3_3_c_masters = 0; public $new_q3_3_3_c_nondegree = 0;
    public $new_q3_3_3_d_doctorate = 0; public $new_q3_3_3_d_masters = 0;
    public $new_q3_3_3_e = 0;
    public $new_q3_4_a = 0; public $new_q3_4_b = 0; public $new_q3_4_c = 0;
    public $new_q3_3_5_1 = 0;
    public $new_q3_6_1_a = 0; public $new_q3_6_1_b = 0;
    public $new_q3_6_1_c = 0; public $new_q3_6_1_d = 0;

    // ─── Live subtotals: prev (past eval) + new (current inputs) ─────────

    public function getSubtotal31Property(): float
    {
        return min((float)(
            ($this->prev_q3_1_1   + $this->new_q3_1_1)
          + ($this->prev_q3_1_2_a + $this->new_q3_1_2_a)
          + ($this->prev_q3_1_2_c + $this->new_q3_1_2_c)
          + ($this->prev_q3_1_2_d + $this->new_q3_1_2_d)
          + ($this->prev_q3_1_2_e + $this->new_q3_1_2_e)
          + ($this->prev_q3_1_2_f + $this->new_q3_1_2_f)
          + ($this->prev_q3_1_3_a + $this->new_q3_1_3_a)
          + ($this->prev_q3_1_3_b + $this->new_q3_1_3_b)
          + ($this->prev_q3_1_3_c + $this->new_q3_1_3_c)
          + ($this->prev_q3_1_4   + $this->new_q3_1_4)
        ), 20);
    }

    public function getSubtotal321Property(): float
    {
        return min((float)(
            ($this->prev_q3_2_1_1_a + $this->new_q3_2_1_1_a)
          + ($this->prev_q3_2_1_1_b + $this->new_q3_2_1_1_b)
          + ($this->prev_q3_2_1_1_c + $this->new_q3_2_1_1_c)
          + ($this->prev_q3_2_1_2   + $this->new_q3_2_1_2)
          + ($this->prev_q3_2_1_3_a + $this->new_q3_2_1_3_a)
          + ($this->prev_q3_2_1_3_b + $this->new_q3_2_1_3_b)
          + ($this->prev_q3_2_1_3_c + $this->new_q3_2_1_3_c)
        ), 10);
    }

    public function getSubtotal322Property(): float
    {
        return min((float)(
            ($this->prev_q3_2_2_1_a + $this->new_q3_2_2_1_a)
          + ($this->prev_q3_2_2_1_b + $this->new_q3_2_2_1_b)
          + ($this->prev_q3_2_2_1_c + $this->new_q3_2_2_1_c)
          + ($this->prev_q3_2_2_2   + $this->new_q3_2_2_2)
          + ($this->prev_q3_2_2_3   + $this->new_q3_2_2_3)
          + ($this->prev_q3_2_2_4   + $this->new_q3_2_2_4)
          + ($this->prev_q3_2_2_5   + $this->new_q3_2_2_5)
          + ($this->prev_q3_2_2_6   + $this->new_q3_2_2_6)
          + ($this->prev_q3_2_2_7   + $this->new_q3_2_2_7)
        ), 20);
    }

    public function getSubtotal33Property(): float
    {
        return min((float)(
            ($this->prev_q3_3_1_a + $this->new_q3_3_1_a)
          + ($this->prev_q3_3_1_b + $this->new_q3_3_1_b)
          + ($this->prev_q3_3_1_c + $this->new_q3_3_1_c)
          + ($this->prev_q3_3_2   + $this->new_q3_3_2)
          + ($this->prev_q3_3_3_a_doctorate + $this->new_q3_3_3_a_doctorate)
          + ($this->prev_q3_3_3_a_masters   + $this->new_q3_3_3_a_masters)
          + ($this->prev_q3_3_3_a_nondegree + $this->new_q3_3_3_a_nondegree)
          + ($this->prev_q3_3_3_b_doctorate + $this->new_q3_3_3_b_doctorate)
          + ($this->prev_q3_3_3_b_masters   + $this->new_q3_3_3_b_masters)
          + ($this->prev_q3_3_3_b_nondegree + $this->new_q3_3_3_b_nondegree)
          + ($this->prev_q3_3_3_c_doctorate + $this->new_q3_3_3_c_doctorate)
          + ($this->prev_q3_3_3_c_masters   + $this->new_q3_3_3_c_masters)
          + ($this->prev_q3_3_3_c_nondegree + $this->new_q3_3_3_c_nondegree)
          + ($this->prev_q3_3_3_d_doctorate + $this->new_q3_3_3_d_doctorate)
          + ($this->prev_q3_3_3_d_masters   + $this->new_q3_3_3_d_masters)
          + ($this->prev_q3_3_3_e           + $this->new_q3_3_3_e)
        ), 30);
    }

    public function getSubtotal34Property(): float
    {
        return (float)(
            ($this->prev_q3_4_a + $this->new_q3_4_a)
          + ($this->prev_q3_4_b + $this->new_q3_4_b)
          + ($this->prev_q3_4_c + $this->new_q3_4_c)
        );
    }

    public function getSubtotal35Property(): float
    {
        return min((float)($this->prev_q3_3_5_1 + $this->new_q3_3_5_1), 5);
    }

    public function getSubtotal36Property(): float
    {
        return min((float)(
            ($this->prev_q3_6_1_a + $this->new_q3_6_1_a)
          + ($this->prev_q3_6_1_b + $this->new_q3_6_1_b)
          + ($this->prev_q3_6_1_c + $this->new_q3_6_1_c)
          + ($this->prev_q3_6_1_d + $this->new_q3_6_1_d)
        ), 10);
    }

    public function getPage1TotalProperty(): float { return $this->subtotal31; }
    public function getPage2TotalProperty(): float { return $this->subtotal31 + $this->subtotal321 + $this->subtotal322; }
    public function getPage3TotalProperty(): float
    {
        return min(
            $this->subtotal31 + $this->subtotal321 + $this->subtotal322
          + $this->subtotal33 + $this->subtotal34 + $this->subtotal35 + $this->subtotal36,
            90
        );
    }

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

        // Load or create ProfessionalDevelopment for THIS assignment
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

        if ($previousAssignment && $previousAssignment->professional_development_id) {
            $prevPd = ProfessionalDevelopment::with([
                'creativeWork','activity','recognition','award','outreach','licensure',
            ])->find($previousAssignment->professional_development_id);
            if ($prevPd) {
                $this->loadPrevFromModel($prevPd);
            }
        }

        // ── Load THIS evaluation's already-saved inputs into new_ fields ──
        // Ensures inputs survive navigating between pages (prev/next buttons).
        $this->loadCurrentIntoNew();
    }

    protected function ensureSubModels(): void
    {
        $pd = $this->professionalDevelopment;

        if (!$pd->creative_work_id) {
            $cw = CreativeWork::create(['q3_1_1'=>0,'q3_1_2_a'=>0,'q3_1_2_c'=>0,'q3_1_2_d'=>0,'q3_1_2_e'=>0,'q3_1_2_f'=>0,'q3_1_3_a'=>0,'q3_1_3_b'=>0,'q3_1_3_c'=>0,'q3_1_4'=>0,'subtotal'=>0]);
            $pd->update(['creative_work_id' => $cw->id]);
        }
        if (!$pd->activity_id) {
            $act = Activity::create(['q3_2_1_1_a'=>0,'q3_2_1_1_b'=>0,'q3_2_1_1_c'=>0,'q3_2_1_2'=>0,'q3_2_1_3_a'=>0,'q3_2_1_3_b'=>0,'q3_2_1_3_c'=>0,'q3_2_2_1_a'=>0,'q3_2_2_1_b'=>0,'q3_2_2_1_c'=>0,'q3_2_2_2'=>0,'q3_2_2_3'=>0,'q3_2_2_4'=>0,'q3_2_2_5'=>0,'q3_2_2_6'=>0,'q3_2_2_7'=>0,'subtotal'=>0]);
            $pd->update(['activity_id' => $act->id]);
        }
        if (!$pd->recognition_id) {
            $rec = Recognition::create(['q3_3_1_a'=>0,'q3_3_1_b'=>0,'q3_3_1_c'=>0,'q3_3_2'=>0,'q3_3_3_a_doctorate'=>0,'q3_3_3_a_masters'=>0,'q3_3_3_a_nondegree'=>0,'q3_3_3_b_doctorate'=>0,'q3_3_3_b_masters'=>0,'q3_3_3_b_nondegree'=>0,'q3_3_3_c_doctorate'=>0,'q3_3_3_c_masters'=>0,'q3_3_3_c_nondegree'=>0,'q3_3_3_d_doctorate'=>0,'q3_3_3_d_masters'=>0,'q3_3_3_e'=>0,'subtotal'=>0]);
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

    /**
     * Load scores from a PREVIOUS evaluation's PD model into prev_ fields (read-only).
     */
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
            $this->prev_q3_2_2_2   = (float)($act->q3_2_2_2   ?? 0);
            $this->prev_q3_2_2_3   = (float)($act->q3_2_2_3   ?? 0);
            $this->prev_q3_2_2_4   = (float)($act->q3_2_2_4   ?? 0);
            $this->prev_q3_2_2_5   = (float)($act->q3_2_2_5   ?? 0);
            $this->prev_q3_2_2_6   = (float)($act->q3_2_2_6   ?? 0);
            $this->prev_q3_2_2_7   = (float)($act->q3_2_2_7   ?? 0);
        }
        if ($rec) {
            $this->prev_q3_3_1_a           = (float)($rec->q3_3_1_a           ?? 0);
            $this->prev_q3_3_1_b           = (float)($rec->q3_3_1_b           ?? 0);
            $this->prev_q3_3_1_c           = (float)($rec->q3_3_1_c           ?? 0);
            $this->prev_q3_3_2             = (float)($rec->q3_3_2             ?? 0);
            $this->prev_q3_3_3_a_doctorate = (float)($rec->q3_3_3_a_doctorate ?? 0);
            $this->prev_q3_3_3_a_masters   = (float)($rec->q3_3_3_a_masters   ?? 0);
            $this->prev_q3_3_3_a_nondegree = (float)($rec->q3_3_3_a_nondegree ?? 0);
            $this->prev_q3_3_3_b_doctorate = (float)($rec->q3_3_3_b_doctorate ?? 0);
            $this->prev_q3_3_3_b_masters   = (float)($rec->q3_3_3_b_masters   ?? 0);
            $this->prev_q3_3_3_b_nondegree = (float)($rec->q3_3_3_b_nondegree ?? 0);
            $this->prev_q3_3_3_c_doctorate = (float)($rec->q3_3_3_c_doctorate ?? 0);
            $this->prev_q3_3_3_c_masters   = (float)($rec->q3_3_3_c_masters   ?? 0);
            $this->prev_q3_3_3_c_nondegree = (float)($rec->q3_3_3_c_nondegree ?? 0);
            $this->prev_q3_3_3_d_doctorate = (float)($rec->q3_3_3_d_doctorate ?? 0);
            $this->prev_q3_3_3_d_masters   = (float)($rec->q3_3_3_d_masters   ?? 0);
            $this->prev_q3_3_3_e           = (float)($rec->q3_3_3_e           ?? 0);
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

    /**
     * Load THIS evaluation's already-saved scores into new_ fields.
     * This ensures inputs are retained when the member navigates between pages.
     * The sub-model columns store ONLY the current session's inputs (not prev+new).
     */
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
            $this->new_q3_2_2_2   = (float)($act->q3_2_2_2   ?? 0);
            $this->new_q3_2_2_3   = (float)($act->q3_2_2_3   ?? 0);
            $this->new_q3_2_2_4   = (float)($act->q3_2_2_4   ?? 0);
            $this->new_q3_2_2_5   = (float)($act->q3_2_2_5   ?? 0);
            $this->new_q3_2_2_6   = (float)($act->q3_2_2_6   ?? 0);
            $this->new_q3_2_2_7   = (float)($act->q3_2_2_7   ?? 0);
        }
        if ($rec) {
            $this->new_q3_3_1_a           = (float)($rec->q3_3_1_a           ?? 0);
            $this->new_q3_3_1_b           = (float)($rec->q3_3_1_b           ?? 0);
            $this->new_q3_3_1_c           = (float)($rec->q3_3_1_c           ?? 0);
            $this->new_q3_3_2             = (float)($rec->q3_3_2             ?? 0);
            $this->new_q3_3_3_a_doctorate = (float)($rec->q3_3_3_a_doctorate ?? 0);
            $this->new_q3_3_3_a_masters   = (float)($rec->q3_3_3_a_masters   ?? 0);
            $this->new_q3_3_3_a_nondegree = (float)($rec->q3_3_3_a_nondegree ?? 0);
            $this->new_q3_3_3_b_doctorate = (float)($rec->q3_3_3_b_doctorate ?? 0);
            $this->new_q3_3_3_b_masters   = (float)($rec->q3_3_3_b_masters   ?? 0);
            $this->new_q3_3_3_b_nondegree = (float)($rec->q3_3_3_b_nondegree ?? 0);
            $this->new_q3_3_3_c_doctorate = (float)($rec->q3_3_3_c_doctorate ?? 0);
            $this->new_q3_3_3_c_masters   = (float)($rec->q3_3_3_c_masters   ?? 0);
            $this->new_q3_3_3_c_nondegree = (float)($rec->q3_3_3_c_nondegree ?? 0);
            $this->new_q3_3_3_d_doctorate = (float)($rec->q3_3_3_d_doctorate ?? 0);
            $this->new_q3_3_3_d_masters   = (float)($rec->q3_3_3_d_masters   ?? 0);
            $this->new_q3_3_3_e           = (float)($rec->q3_3_3_e           ?? 0);
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

    /**
     * Persist current new_ inputs to DB sub-models (NOT the final prev+new total).
     * Called on page navigation so inputs survive going back and forth between pages.
     */
    protected function persistCurrentInputs(): void
    {
        $pd = $this->professionalDevelopment;

        $cw_fields = ['q3_1_1','q3_1_2_a','q3_1_2_c','q3_1_2_d','q3_1_2_e','q3_1_2_f','q3_1_3_a','q3_1_3_b','q3_1_3_c','q3_1_4'];
        $cwData = [];
        foreach ($cw_fields as $f) { $cwData[$f] = (float)$this->{'new_'.$f}; }
        $cwData['subtotal'] = min(array_sum($cwData), 20);
        $pd->creativeWork->update($cwData);

        $act_fields = ['q3_2_1_1_a','q3_2_1_1_b','q3_2_1_1_c','q3_2_1_2','q3_2_1_3_a','q3_2_1_3_b','q3_2_1_3_c','q3_2_2_1_a','q3_2_2_1_b','q3_2_2_1_c','q3_2_2_2','q3_2_2_3','q3_2_2_4','q3_2_2_5','q3_2_2_6','q3_2_2_7'];
        $actData = [];
        foreach ($act_fields as $f) { $actData[$f] = (float)$this->{'new_'.$f}; }
        $actData['subtotal'] = min(array_sum($actData), 20);
        $pd->activity->update($actData);

        $rec_fields = ['q3_3_1_a','q3_3_1_b','q3_3_1_c','q3_3_2','q3_3_3_a_doctorate','q3_3_3_a_masters','q3_3_3_a_nondegree','q3_3_3_b_doctorate','q3_3_3_b_masters','q3_3_3_b_nondegree','q3_3_3_c_doctorate','q3_3_3_c_masters','q3_3_3_c_nondegree','q3_3_3_d_doctorate','q3_3_3_d_masters','q3_3_3_e'];
        $recData = [];
        foreach ($rec_fields as $f) { $recData[$f] = (float)$this->{'new_'.$f}; }
        $recData['subtotal'] = min(array_sum($recData), 30);
        $pd->recognition->update($recData);

        $awData = [
            'q3_4_a' => (float)$this->new_q3_4_a,
            'q3_4_b' => (float)$this->new_q3_4_b,
            'q3_4_c' => (float)$this->new_q3_4_c,
        ];
        $awData['subtotal'] = array_sum($awData);
        $pd->award->update($awData);

        $outVal = (float)$this->new_q3_3_5_1;
        $pd->outreach->update(['q3_3_5_1' => $outVal, 'subtotal' => min($outVal, 5)]);

        $licData = [
            'q3_6_1_a' => (float)$this->new_q3_6_1_a,
            'q3_6_1_b' => (float)$this->new_q3_6_1_b,
            'q3_6_1_c' => (float)$this->new_q3_6_1_c,
            'q3_6_1_d' => (float)$this->new_q3_6_1_d,
        ];
        $licData['subtotal'] = min(array_sum($licData), 10);
        $pd->licensure->update($licData);
    }

    /**
     * Called ONLY on final Submit.
     * Writes the combined (prev + new) total to professional_developments.subtotal.
     */
    protected function finalizeAndSave(): void
    {
        $this->persistCurrentInputs();

        // The page3Total computed property already includes prev + new with all caps applied.
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

    /**
     * Navigate between pages, persisting inputs so they survive the page switch.
     */
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

    /**
     * Final submit: combine prev + new, mark complete, redirect to dashboard.
     */
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