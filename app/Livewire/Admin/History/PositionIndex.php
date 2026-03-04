<?php

namespace App\Livewire\Admin\History;

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
    public string $filterCollege = '';
    public string $filterDepartment = '';
    public int $perPage = 5;

    protected $paginationTheme = 'tailwind';

    #[On('refreshPositionHistory')]
    public function refreshPositionHistory()
    {
        $this->resetPage();
    }

    public function updatingSearch()
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

            $this->dispatch('refreshPositionHistory');
            session()->flash('success', 'Position history record has been deleted successfully');

        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete position history record');
        }
    }

    public function deleteConfirmed($id)
    {
        $this->dispatch(
            'confirmation',
            id: $id,
            title: "Are you sure to delete this position record?",
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
                    ->orWhereHas('department', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    })
            )
            ->when(
                $this->filterCollege,
                fn($q) => $q->where('college_id', $this->filterCollege)
            )
            ->when(
                $this->filterDepartment,
                fn($q) => $q->where('department_id', $this->filterDepartment)
            )
            // Only positions whose end_date is in the past (expired)
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->toDateString())
            ->orderBy('end_date', 'desc')
            ->paginate($this->perPage);
    }

    public function render()
    {
        $colleges = College::orderBy('name')->get();

        return view('livewire.admin.history.position-index', [
            'positions'         => $this->filteredPositions,
            'colleges'          => $colleges,
            'filterDepartments' => $this->filterCollege
                ? Department::where('college_id', $this->filterCollege)->orderBy('name')->get()
                : [],
        ]);
    }
}