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
        if (!empty($this->college_id)) {
            $this->departments = Department::where('college_id', $this->college_id)
                ->orderBy('name')
                ->get();
        } else {
            $this->departments = [];
        }
    }

    public function updatedDepartmentId()
    {

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

        if (!empty($this->college_id) && empty($this->departments)) {
            $this->departments = Department::where('college_id', $this->college_id)
                ->orderBy('name')
                ->get();
        }

        $query = JobApplication::with(['applicant.user', 'position.college', 'position.department']);

        if (in_array($this->status, ['pending', 'approve', 'decline'])) {
            $query->where('status', $this->status);
        }

        if ($this->college_id) {
            $query->whereHas('position', function ($q) {
                $q->where('college_id', $this->college_id);
            });
        }

        if ($this->department_id) {
            $query->whereHas('position', function ($q) {
                $q->where('department_id', $this->department_id);
            });
        }

        if ($this->position) {
            $query->whereHas('position', function ($q) {
                $q->where('name', $this->position);
            });
        }

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
            'positions' => Position::when($this->college_id, function ($q) {
                    $q->where('college_id', $this->college_id);
                })->when($this->department_id, function ($q) {
                    $q->where('department_id', $this->department_id);
                })->orderBy('name')->get(),
        ]);
    }
}