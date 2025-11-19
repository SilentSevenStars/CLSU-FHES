<?php

namespace App\Livewire\Admin;

use App\Livewire\Export\ScheduledApplicantExport;
use App\Models\JobApplication;
use App\Models\Position;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ScheduledApplicant extends Component
{
    use WithPagination;

    public $selectedPosition = '';

    public function updatingSelectedPosition()
    {
        $this->resetPage();
    }

    public function exportExcel()
    {
        if (!$this->selectedPosition) {
            return session()->flash('error', 'Please select a position before exporting.');
        }

        return Excel::download(new ScheduledApplicantExport($this->selectedPosition), 'scheduled_applicants.xlsx');
    }

    public function exportPDF()
    {
        if (!$this->selectedPosition) {
            return session()->flash('error', 'Please select a position before exporting.');
        }

        $applications = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->where('position_id', $this->selectedPosition)
            ->get();

        $pdf = Pdf::loadView('exports.scheduled-applicants-pdf', [
            'applications' => $applications
        ])->setPaper('legal', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'scheduled_applicants.pdf');
    }

    public function render()
    {
        // dropdown list
        $positions = Position::whereIn('status', ['vacant', 'promotion'])
            ->orderBy('name')
            ->get();

        // If no position selected → show empty paginator
        if (empty($this->selectedPosition)) {
            $emptyPaginator = new LengthAwarePaginator(
                collect(),    // empty data
                0,            // total
                10,           // per page
                1,            // current page
                ['path' => request()->url()]
            );

            return view('livewire.admin.scheduled-applicant', [
                'applications'  => $emptyPaginator,
                'pendingCount'  => 0,
                'positions'     => $positions,
            ])->layout('layouts.app');
        }

        // Query applicants for the selected position
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
