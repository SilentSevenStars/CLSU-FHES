<?php

namespace App\Livewire\Admin;

use App\Models\Evaluation;
use App\Models\JobApplication;
use App\Models\Position;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduledApplicant extends Component
{
    use WithPagination;

    public $selectedPositionName = '';
    public $selectedDate = '';

    public function updatingSelectedPositionName()
    {
        $this->selectedDate = '';
        $this->resetPage();
    }

    public function updatingSelectedDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Unique position names from vacant/promotion positions
        $positionNames = Position::whereIn('status', ['vacant', 'promotion'])
            ->orderBy('name')
            ->pluck('name')
            ->unique()
            ->values();

        // Unique interview dates from evaluations, filtered by selected position name
        $availableDates = collect();
        if ($this->selectedPositionName) {
            $positionIds = Position::whereIn('status', ['vacant', 'promotion'])
                ->where('name', $this->selectedPositionName)
                ->pluck('id');

            $jobApplicationIds = JobApplication::whereIn('position_id', $positionIds)
                ->pluck('id');

            $availableDates = Evaluation::whereIn('job_application_id', $jobApplicationIds)
                ->orderBy('interview_date')
                ->pluck('interview_date')
                ->unique()
                ->values();
        }

        // No position selected — return empty paginator
        if (empty($this->selectedPositionName)) {
            $emptyPaginator = new LengthAwarePaginator(
                collect(), 0, 10, 1,
                ['path' => request()->url()]
            );

            return view('livewire.admin.scheduled-applicant', [
                'applications'   => $emptyPaginator,
                'pendingCount'   => 0,
                'positionNames'  => $positionNames,
                'availableDates' => $availableDates,
            ]);
        }

        // Get all position IDs matching the selected name
        $positionIds = Position::whereIn('status', ['vacant', 'promotion'])
            ->where('name', $this->selectedPositionName)
            ->pluck('id');

        $baseQuery = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->whereIn('position_id', $positionIds)
            ->whereHas('evaluation'); // only show applicants that have an evaluation (scheduled)

        // Further filter by interview date if selected
        if ($this->selectedDate) {
            $baseQuery->whereHas('evaluation', function ($q) {
                $q->where('interview_date', $this->selectedDate);
            });
        }

        $pendingCount = (clone $baseQuery)->count();
        $applications = $baseQuery->paginate(10);

        return view('livewire.admin.scheduled-applicant', [
            'applications'   => $applications,
            'pendingCount'   => $pendingCount,
            'positionNames'  => $positionNames,
            'availableDates' => $availableDates,
        ]);
    }
}