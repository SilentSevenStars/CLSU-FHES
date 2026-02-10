<?php

namespace App\Livewire\Admin\Position;

use App\Models\College;
use App\Models\Department;
use App\Models\Position;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PositionIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'all';
    public string $filterCollege = '';      
    public string $filterDepartment = '';   
    public int $perPage = 5;

    protected $paginationTheme = 'tailwind';

    #[On('refreshPositions')]
    public function refreshPositions()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingFilterCollege()
    {
        $this->filterDepartment = '';
        $this->resetPage();
    }

    public function updatingFilterDepartment()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    #[On('destroy')]
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $position = Position::findOrFail($id);
            $position->delete();
            DB::commit();

            $this->dispatch('refreshPositions');
            session()->flash('success', 'Position has been deleted successfully');
            
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete position');
        }
    }

    public function deleteConfirmed($id)
    {
        $this->dispatch(
            'confirmation',
            id: $id,
            title: "Are you sure to delete this position?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        );
    }

    public function getFilteredPositionsProperty()
    {
        return Position::with(['college', 'department']) 
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('department', function($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    })
            )
            ->when($this->filter !== 'all', function ($q) {
                if ($this->filter === 'vacant') {
                    $q->where('status', 'vacant');
                } elseif ($this->filter === 'promotion') {
                    $q->where('status', 'promotion');
                } elseif ($this->filter === 'none') {
                    $q->where('status', 'none');
                }
            })
            ->when(
                $this->filterCollege,
                fn($q) => $q->where('college_id', $this->filterCollege)  
            )
            ->when(
                $this->filterDepartment,
                fn($q) => $q->where('department_id', $this->filterDepartment)  
            )
            // Ensure end_date is not in the past
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function getVacantCountProperty()
    {
        return Position::where('status', 'vacant')
            ->whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count();
    }

    public function getPromotionCountProperty()
    {
        return Position::where('status', 'promotion')
            ->whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count();
    }

    public function render()
    {
        $colleges = College::orderBy('name')->get();
        return view('livewire.admin.position.position-index', [
            'positions' => $this->filteredPositions,
            'vacant' => $this->vacantCount,
            'promotion' => $this->promotionCount,
            'colleges' => $colleges,
            'filterDepartments' => $this->filterCollege
                ? Department::where('college_id', $this->filterCollege)->orderBy('name')->get()
                : []
        ]);
    }
}