<?php

namespace App\Livewire\Admin;

use App\Exports\ScheduledApplicantExport as ExportsScheduledApplicantExport;
use App\Models\JobApplication;
use App\Models\Position;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ScheduledApplicant extends Component
{
    Use WithPagination;

    public $selectedPosition = '';

    public function updatingSelectedPosition()
    {
        $this->resetPage();
    }

    protected function sanitizeString($value)
    {
        if (is_null($value)) return '';
        return (string) iconv('UTF-8', 'UTF-8//IGNORE', $value);
    }

    public function exportExcel()
    {
        if (!$this->selectedPosition) {
            session()->flash('error', 'Please select a position first.');
            return;
        }

        $position = Position::find($this->selectedPosition);
        if (!$position) {
            session()->flash('error', 'Selected position not found.');
            return;
        }

        return Excel::download(
            new ExportsScheduledApplicantExport($this->selectedPosition),
            'Scheduled Applicants - ' . $this->sanitizeString($position->name) . '.xlsx'
        );
    }

    public function exportPDF()
    {
        if (!$this->selectedPosition) {
            session()->flash('error', 'Please select a position first.');
            return;
        }

        $position = Position::find($this->selectedPosition);
        if (!$position) {
            session()->flash('error', 'Selected position not found.');
            return;
        }

        $applications = JobApplication::with(['applicant.user', 'position'])
            ->where('position_id', $this->selectedPosition)
            ->get()
            ->map(function ($a) {
                return (object)[
                    'position_name'      => iconv('UTF-8', 'UTF-8//IGNORE', optional($a->position)->name ?? ''),
                    'present_position'   => iconv('UTF-8', 'UTF-8//IGNORE', $a->present_position ?? ''),
                    'education'          => iconv('UTF-8', 'UTF-8//IGNORE', $a->education ?? ''),
                    'experience'         => iconv('UTF-8', 'UTF-8//IGNORE', (string)$a->experience),
                    'training'           => iconv('UTF-8', 'UTF-8//IGNORE', $a->training ?? ''),
                    'eligibility'        => iconv('UTF-8', 'UTF-8//IGNORE', $a->eligibility ?? ''),
                    'other_involvement'  => iconv('UTF-8', 'UTF-8//IGNORE', $a->other_involvement ?? ''),
                    'phone_number'       => iconv('UTF-8', 'UTF-8//IGNORE', optional($a->applicant)->phone_number ?? ''),
                    'address'            => iconv('UTF-8', 'UTF-8//IGNORE', optional($a->applicant)->address ?? ''),
                    'email'              => iconv('UTF-8', 'UTF-8//IGNORE', optional(optional($a->applicant)->user)->email ?? ''),
                ];
            });

        $pdf = Pdf::loadView('export.scheduled-applicant-pdf', [
            'applications' => $applications,
            'position'     => (object)[
                'name' => iconv('UTF-8', 'UTF-8//IGNORE', $position->name ?? '')
            ],
        ])->setPaper('legal', 'landscape');

        return $pdf->download('Scheduled Applicants - ' . $this->sanitizeString($position->name) . '.pdf');
    }

    public function render()
    {
        $positions = Position::whereIn('status', ['vacant', 'promotion'])
            ->orderBy('name')
            ->get();

        if (empty($this->selectedPosition)) {
            $emptyPaginator = new LengthAwarePaginator(
                collect(),
                0,
                10,
                1,
                ['path' => request()->url()]
            );

            return view('livewire.admin.scheduled-applicant', [
                'applications'  => $emptyPaginator,
                'pendingCount'  => 0,
                'positions'     => $positions,
            ])->layout('layouts.app');
        }

        $baseQuery = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->where('position_id', $this->selectedPosition);

        $pendingCount = (clone $baseQuery)->count();

        $applications = $baseQuery->paginate(10);

        return view('livewire.admin.scheduled-applicant', [
            'applications'  => $applications,
            'pendingCount'  => $pendingCount,
            'positions'     => $positions,
        ])->layout('layouts.app');
    }
}
