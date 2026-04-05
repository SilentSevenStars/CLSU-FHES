<?php

namespace App\Livewire\Applicant;

use App\Models\SpmsEntry;
use App\Models\SpmsIpr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SpmsIprForm extends Component
{
    // ── IPR meta ────────────────────────────────────────────────
    public ?SpmsIpr $ipr = null;
    public string $evaluation_period = '';
    public string $period_start = '';
    public string $period_end = '';

    // ── Section rows (arrays of entry data keyed by temp id) ────
    public array $strategic = [];   // Section A – 40 %
    public array $core      = [];   // Section B – 40 %
    public array $support   = [];   // Section C – 20 %

    // ── UI state ────────────────────────────────────────────────
    public string $activeTab = 'A';
    public bool $showDeleteModal = false;
    public ?string $deleteTarget = null; // "A-{idx}" | "B-{idx}" | "C-{idx}"

    // ─────────────────────────────────────────────────────────────
    public function mount(?int $iprId = null): void
    {
        if ($iprId) {
            $this->ipr = SpmsIpr::with('entries')->findOrFail($iprId);
            $this->evaluation_period = $this->ipr->evaluation_period;
            $this->period_start      = $this->ipr->period_start->format('Y-m-d');
            $this->period_end        = $this->ipr->period_end->format('Y-m-d');

            foreach ($this->ipr->entries as $entry) {
                $row = $this->makeRow($entry);
                match($entry->section) {
                    'A' => $this->strategic[] = $row,
                    'B' => $this->core[]      = $row,
                    'C' => $this->support[]   = $row,
                };
            }
        }

        // Seed at least one blank row per section
        if (empty($this->strategic)) $this->strategic[] = $this->blankRow();
        if (empty($this->core))      $this->core[]      = $this->blankRow();
        if (empty($this->support))   $this->support[]   = $this->blankRow();
    }

    // ── Row helpers ─────────────────────────────────────────────
    private function blankRow(): array
    {
        return [
            'db_id'                  => null,
            'output'                 => '',
            'success_indicators'     => '',
            'actual_accomplishments' => '',
        ];
    }

    private function makeRow(SpmsEntry $entry): array
    {
        return [
            'db_id'                  => $entry->id,
            'output'                 => $entry->output,
            'success_indicators'     => $entry->success_indicators,
            'actual_accomplishments' => $entry->actual_accomplishments ?? '',
        ];
    }

    // ── Add / remove rows ───────────────────────────────────────
    public function addRow(string $section): void
    {
        match($section) {
            'A' => $this->strategic[] = $this->blankRow(),
            'B' => $this->core[]      = $this->blankRow(),
            'C' => $this->support[]   = $this->blankRow(),
        };
    }

    public function confirmDelete(string $key): void
    {
        $this->deleteTarget   = $key;
        $this->showDeleteModal = true;
    }

    public function removeRow(): void
    {
        if (!$this->deleteTarget) return;

        [$section, $idx] = explode('-', $this->deleteTarget);
        $idx = (int)$idx;

        $rows = match($section) {
            'A' => &$this->strategic,
            'B' => &$this->core,
            'C' => &$this->support,
        };

        // Delete from DB if it exists
        if (!empty($rows[$idx]['db_id'])) {
            SpmsEntry::find($rows[$idx]['db_id'])?->delete();
        }

        array_splice($rows, $idx, 1);
        if (empty($rows)) $rows[] = $this->blankRow();

        $this->showDeleteModal = false;
        $this->deleteTarget   = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteTarget   = null;
    }

    // ── Save (draft) ────────────────────────────────────────────
    public function saveDraft(): void
    {
        $this->validateMeta();
        $ipr = $this->upsertIpr('draft');
        $this->persistEntries($ipr);
        $ipr->recompute();
        $this->ipr = $ipr->fresh('entries');
        session()->flash('success', 'SPMS IPR draft saved successfully.');
    }

    // ── Submit ───────────────────────────────────────────────────
    public function submit(): void
    {
        $this->validateAll();
        $ipr = $this->upsertIpr('submitted');
        $this->persistEntries($ipr);
        $ipr->recompute();
        $this->ipr = $ipr->fresh('entries');
        session()->flash('success', 'SPMS IPR submitted successfully.');
        $this->dispatch('ipr-submitted');
    }

    // ── Validation ───────────────────────────────────────────────
    private function validateMeta(): void
    {
        $this->validate([
            'evaluation_period' => 'required|string|max:255',
            'period_start'      => 'required|date',
            'period_end'        => 'required|date|after_or_equal:period_start',
        ]);
    }

    private function validateAll(): void
    {
        $this->validateMeta();
        $this->validate([
            'strategic.*.output'             => 'required|string',
            'strategic.*.success_indicators' => 'required|string',
            'strategic.*.actual_accomplishments' => 'required|string',
            'core.*.output'                  => 'required|string',
            'core.*.success_indicators'      => 'required|string',
            'core.*.actual_accomplishments'  => 'required|string',
            'support.*.output'               => 'required|string',
            'support.*.success_indicators'   => 'required|string',
            'support.*.actual_accomplishments' => 'required|string',
        ], [
            '*.*.output.required'                  => 'Output is required.',
            '*.*.success_indicators.required'      => 'Success indicators are required.',
            '*.*.actual_accomplishments.required'  => 'Actual accomplishments are required.',
        ]);
    }

    // ── DB persistence ───────────────────────────────────────────
    private function upsertIpr(string $status): SpmsIpr
    {
        $applicantId = Auth::user()->applicant->id;

        $data = [
            'applicant_id'      => $applicantId,
            'evaluation_period' => $this->evaluation_period,
            'period_start'      => $this->period_start,
            'period_end'        => $this->period_end,
            'status'            => $status,
        ];

        if ($this->ipr) {
            $this->ipr->update($data);
            return $this->ipr;
        }

        return SpmsIpr::create($data);
    }

    private function persistEntries(SpmsIpr $ipr): void
    {
        $this->syncSection($ipr, 'A', $this->strategic);
        $this->syncSection($ipr, 'B', $this->core);
        $this->syncSection($ipr, 'C', $this->support);
    }

    private function syncSection(SpmsIpr $ipr, string $section, array $rows): void
    {
        foreach ($rows as $idx => $row) {
            $data = [
                'spms_ipr_id'            => $ipr->id,
                'section'                => $section,
                'sort_order'             => $idx,
                'output'                 => $row['output'],
                'success_indicators'     => $row['success_indicators'],
                'actual_accomplishments' => $row['actual_accomplishments'] ?: null,
            ];

            if (!empty($row['db_id'])) {
                SpmsEntry::where('id', $row['db_id'])->update($data);
            } else {
                $entry = SpmsEntry::create($data);
                // Update local array with new db_id
                match($section) {
                    'A' => $this->strategic[$idx]['db_id'] = $entry->id,
                    'B' => $this->core[$idx]['db_id']      = $entry->id,
                    'C' => $this->support[$idx]['db_id']   = $entry->id,
                };
            }
        }
    }

    // ── Computed scores for display ──────────────────────────────
    public function getSectionSubtotal(string $section): ?float
    {
        $entries = match($section) {
            'A' => $this->ipr?->strategicEntries,
            'B' => $this->ipr?->coreEntries,
            'C' => $this->ipr?->supportEntries,
        };

        if (!$entries || $entries->isEmpty()) return null;

        $rated = $entries->filter(fn($e) => $e->isRated());
        if ($rated->isEmpty()) return null;

        return round($rated->map(fn($e) => $e->average)->avg(), 3);
    }

    public function render()
    {
        return view('livewire.applicant.spms-ipr-form');
    }
}