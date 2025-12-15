<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Department;
use App\Models\JobApplication;
use App\Models\Position;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Applicant extends Component
{
    use WithPagination;

    public $status = 'pending';
    public $college_id = '';
    public $department_id = '';
    public $departments = [];
    public $perPage = 10;
    public $search = '';
    public $position = '';

    public function updatingStatus()
    {
        $this->resetPage();
    }
    public function updatingCollegeId()
    {
        $this->department_id = '';
        $this->position = '';
        $this->resetPage();
        $this->updatedCollegeId();
    }
    public function updatingDepartmentId()
    {
        $this->position = '';
        $this->resetPage();
        $this->updatedDepartmentId();
    }

    public function updatedCollegeId()
    {
        $college = trim($this->college_id ?? '');
        if (!empty($college)) {
            $this->departments = Department::whereRaw('lower(college) = ?', [strtolower($college)])->get();
            if (count($this->departments) === 0) {
                $this->departments = Department::where('college', 'like', "%{$college}%")->get();
            }
        } else {
            $this->departments = [];
        }
    }

    public function updatedDepartmentId()
    {
        // When department changes, positions will be filtered in render via Position::when
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }
    public function updatingPosition()
    {
        $this->resetPage();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $now = Carbon::now();

        // Counts
        $pendingCount = JobApplication::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', 'pending')
            ->count();

        $approvedCount = JobApplication::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', 'approve')
            ->count();

        $declinedCount = JobApplication::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', 'decline')
            ->count();

        // Ensure departments are populated when a college is selected.
        // This covers cases where frontend modifiers prevented the `updatedCollegeId` hook.
        if (!empty($this->college_id) && empty($this->departments)) {
            $college = trim($this->college_id ?? '');
            $this->departments = Department::whereRaw('lower(college) = ?', [strtolower($college)])->get();
            if (count($this->departments) === 0) {
                $this->departments = Department::where('college', 'like', "%{$college}%")->get();
            }
        }

        // Main Query
        $query = JobApplication::with(['applicant.user', 'position']);

        // Status filter (only 3)
        if (in_array($this->status, ['pending', 'approve', 'decline'])) {
            $query->where('status', $this->status);
        }

        // College filter: the select now provides the college NAME.
        if ($this->college_id) {
            $query->whereHas('position', function ($q) {
                $q->where('college', $this->college_id);
            });
        }

        // Department filter: the select now provides the department NAME.
        if ($this->department_id) {
            $query->whereHas('position', function ($q) {
                $q->where('department', $this->department_id);
            });
        }

        // Position filter: select provides the position NAME.
        if ($this->position) {
            $query->whereHas('position', function ($q) {
                $q->where('name', $this->position);
            });
        }

        // Search filter
        if ($this->search) {
            $query->whereHas('applicant.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        $applications = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.applicant', [
            'applications' => $applications,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'declinedCount' => $declinedCount,
            'colleges' => College::all(),
            'departments' => $this->departments,
            'positions' => Position::when($this->college_id, function ($q) {
                    $q->where('college', $this->college_id);
                })->when($this->department_id, function ($q) {
                    $q->where('department', $this->department_id);
                })->get(),
        ]);
    }
}
