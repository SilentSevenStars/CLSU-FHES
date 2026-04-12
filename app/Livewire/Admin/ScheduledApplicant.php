<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Department;
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
    public $selectedCollegeId    = '';
    public $selectedDepartmentId = '';
    public $selectedDate         = '';

    // When position changes, reset college, department, and date
    public function updatingSelectedPositionName()
    {
        $this->selectedCollegeId    = '';
        $this->selectedDepartmentId = '';
        $this->selectedDate         = '';
        $this->resetPage();
    }

    // When college changes, reset department and date
    public function updatingSelectedCollegeId()
    {
        $this->selectedDepartmentId = '';
        $this->selectedDate         = '';
        $this->resetPage();
    }

    // When department changes, reset date only
    public function updatingSelectedDepartmentId()
    {
        $this->selectedDate = '';
        $this->resetPage();
    }

    public function updatingSelectedDate()
    {
        $this->resetPage();
    }

    public function print()
    {
        if (empty($this->selectedPositionName)) {
            session()->flash('error', 'Please select a position first.');
            return;
        }

        $positionIds = $this->getFilteredPositionIds();

        $query = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->whereIn('position_id', $positionIds)
            ->whereHas('evaluation');

        if ($this->selectedDate) {
            $query->whereHas('evaluation', function ($q) {
                $q->where('interview_date', $this->selectedDate);
            });
        }

        $applicants = $query->get()->map(function ($app) {
            $a = $app->applicant;
            $p = $app->position;
            $u = $a->user;

            $addressParts = array_filter([
                $a->street   ?? null,
                $a->barangay ?? null,
                $a->city     ?? null,
                $a->province ?? null,
            ]);
            $address = implode(', ', $addressParts) ?: 'N/A';

            $middleInitial = $a->middle_name
                ? strtoupper(substr($a->middle_name, 0, 1)) . '.'
                : '';
            $fullName = trim(
                $a->first_name . ' ' .
                $middleInitial . ' ' .
                $a->last_name .
                ($a->suffix ? ', ' . $a->suffix : '')
            );

            $experience = (!empty($app->experience) && $app->experience != 0)
                ? $app->experience . ' year(s)'
                : 'None Required';

            $training = (!empty($app->training) && $app->training != 0)
                ? $app->training . ' hour(s)'
                : 'None Required';

            return [
                'name'              => $fullName,
                'position_name'     => $p->name ?? 'N/A',
                'present_pos'       => $app->present_position ?? 'N/A',
                'education'         => $app->education ?? 'N/A',
                'experience'        => $experience,
                'training'          => $training,
                'eligibility'       => $app->eligibility ?? 'N/A',
                'other'             => $app->other_involvement ?? 'N/A',
                'requirements_file' => $app->requirements_file ?? 'N/A',
                'cp_number'         => $a->phone_number ?? 'N/A',
                'address'           => $address,
                'email'             => $u->email ?? 'N/A',
            ];
        })->values()->toArray();

        $hiringDate    = $this->selectedDate
            ? \Carbon\Carbon::parse($this->selectedDate)->format('F j, Y')
            : now()->format('F j, Y');
        $generatedDate = now()->format('F d, Y h:i A');
        $positionName  = $this->selectedPositionName;

        $html = view('print.faculty-hiring-print', compact(
            'applicants',
            'positionName',
            'hiringDate',
            'generatedDate'
        ))->render();

        $this->dispatch('openPrintTab', html: $html);
    }

    /**
     * Get position IDs filtered by position name, college, and department.
     * College and department filter against job_applications -> applicant
     * since positions may not have college_id/department_id set.
     */
    private function getFilteredPositionIds()
    {
        // Start with positions matching the selected name
        $positionIds = Position::where('name', $this->selectedPositionName)->pluck('id');

        // Further filter job applications by college and department on the applicant
        if ($this->selectedCollegeId || $this->selectedDepartmentId) {
            $query = JobApplication::whereIn('position_id', $positionIds)
                ->whereHas('applicant', function ($q) {
                    if ($this->selectedCollegeId) {
                        $q->where('college_id', $this->selectedCollegeId);
                    }
                    if ($this->selectedDepartmentId) {
                        $q->where('department_id', $this->selectedDepartmentId);
                    }
                });

            // Return the position IDs that still have matching applications
            return $query->pluck('position_id')->unique()->values();
        }

        return $positionIds;
    }

    public function render()
    {
        // 1. All unique position names — always the first filter
        $positionNames = Position::orderBy('name')
            ->pluck('name')
            ->unique()
            ->values();

        // 2. All colleges — shown after position is selected
        $colleges = collect();
        if ($this->selectedPositionName) {
            $colleges = College::orderBy('name')->get();
        }

        // 3. Departments filtered by selected college — shown after college is selected
        $departments = collect();
        if ($this->selectedPositionName && $this->selectedCollegeId) {
            $departments = Department::where('college_id', $this->selectedCollegeId)
                ->orderBy('name')
                ->get();
        }

        // 4. Available interview dates scoped to all active filters
        $availableDates = collect();
        if ($this->selectedPositionName) {
            $positionIds = $this->getFilteredPositionIds();
            $jobAppIds   = JobApplication::whereIn('position_id', $positionIds)->pluck('id');

            if ($this->selectedCollegeId || $this->selectedDepartmentId) {
                $jobAppIds = JobApplication::whereIn('position_id', $positionIds)
                    ->whereHas('applicant', function ($q) {
                        if ($this->selectedCollegeId) {
                            $q->where('college_id', $this->selectedCollegeId);
                        }
                        if ($this->selectedDepartmentId) {
                            $q->where('department_id', $this->selectedDepartmentId);
                        }
                    })->pluck('id');
            }

            $availableDates = Evaluation::whereIn('job_application_id', $jobAppIds)
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
                'colleges'       => $colleges,
                'departments'    => $departments,
                'availableDates' => $availableDates,
            ]);
        }

        $positionIds = $this->getFilteredPositionIds();

        $baseQuery = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->whereIn('position_id', $positionIds)
            ->whereHas('evaluation');

        if ($this->selectedCollegeId || $this->selectedDepartmentId) {
            $baseQuery->whereHas('applicant', function ($q) {
                if ($this->selectedCollegeId) {
                    $q->where('college_id', $this->selectedCollegeId);
                }
                if ($this->selectedDepartmentId) {
                    $q->where('department_id', $this->selectedDepartmentId);
                }
            });
        }

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
            'colleges'       => $colleges,
            'departments'    => $departments,
            'availableDates' => $availableDates,
        ]);
    }
}