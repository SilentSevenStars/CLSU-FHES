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
    public $college_id = '';        // Now stores college ID
    public $department_id = '';     // Now stores department ID
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

    /**
     * When college is selected, load its departments
     * Now uses college_id foreign key instead of college name
     */
    public function updatedCollegeId()
    {
        if (!empty($this->college_id)) {
            // Load departments filtered by college_id (foreign key)
            $this->departments = Department::where('college_id', $this->college_id)
                ->orderBy('name')
                ->get();
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

        // Ensure departments are populated when a college is selected
        if (!empty($this->college_id) && empty($this->departments)) {
            $this->departments = Department::where('college_id', $this->college_id)
                ->orderBy('name')
                ->get();
        }

        // Main Query with eager loading
        $query = JobApplication::with(['applicant.user', 'position.college', 'position.department']);

        // Status filter
        if (in_array($this->status, ['pending', 'approve', 'decline'])) {
            $query->where('status', $this->status);
        }

        // College filter: now uses college_id foreign key
        if ($this->college_id) {
            $query->whereHas('position', function ($q) {
                $q->where('college_id', $this->college_id);
            });
        }

        // Department filter: now uses department_id foreign key
        if ($this->department_id) {
            $query->whereHas('position', function ($q) {
                $q->where('department_id', $this->department_id);
            });
        }

        // Position filter: still uses position name
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
            'colleges' => College::orderBy('name')->get(),
            'departments' => $this->departments,
            // Filter positions by college_id and department_id (foreign keys)
            'positions' => Position::when($this->college_id, function ($q) {
                    $q->where('college_id', $this->college_id);
                })->when($this->department_id, function ($q) {
                    $q->where('department_id', $this->department_id);
                })->orderBy('name')->get(),
        ]);
    }
}