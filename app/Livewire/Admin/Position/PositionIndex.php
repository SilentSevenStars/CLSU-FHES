<?php

namespace App\Livewire\Admin\Position;

use App\Models\College;
use App\Models\Department;
use App\Models\Position;
use App\Services\AccountActivityService;
use Exception;
use Illuminate\Support\Facades\Auth;
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

    // Show modal
    public $showModal   = false;
    public $viewPosition = null;

    protected $paginationTheme = 'tailwind';

    #[On('refreshPositions')]
    public function refreshPositions()
    {
        $this->resetPage();
    }

    public function updatingSearch()           { $this->resetPage(); }
    public function updatingPerPage()          { $this->resetPage(); }

    public function updatingFilterCollege()
    {
        $this->filterDepartment = '';
        $this->resetPage();
    }

    public function updatingFilterDepartment() { $this->resetPage(); }

    // ─────────────────────────────────────────────────────────────────────────
    // Show modal
    // ─────────────────────────────────────────────────────────────────────────

    public function showPosition($id)
    {
        $this->viewPosition = Position::with(['college', 'department'])->findOrFail($id);
        $this->showModal    = true;
    }

    public function closeModal()
    {
        $this->showModal    = false;
        $this->viewPosition = null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Delete
    // ─────────────────────────────────────────────────────────────────────────

    #[On('destroy')]
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $position = Position::with(['college', 'department'])->findOrFail($id);

            // Capture details before deletion
            $positionName   = $position->name;
            $collegeName    = $position->college->name  ?? 'Various Colleges';
            $departmentName = $position->department->name ?? 'Various Departments';

            $position->delete();
            DB::commit();

            // ── Activity log ─────────────────────────────────────────────────
            AccountActivityService::log(
                Auth::user(),
                "Deleted position \"{$positionName}\" — College: {$collegeName}, Department: {$departmentName}, Position ID: {$id}."
            );
            // ─────────────────────────────────────────────────────────────────

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

    // ─────────────────────────────────────────────────────────────────────────
    // Query
    // ─────────────────────────────────────────────────────────────────────────

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
                $this->filterCollege === 'various',
                fn($q) => $q->whereNull('college_id')
            )
            ->when(
                $this->filterCollege && $this->filterCollege !== 'various',
                fn($q) => $q->where('college_id', $this->filterCollege)
            )
            ->when(
                $this->filterDepartment === 'various',
                fn($q) => $q->whereNull('department_id')
            )
            ->when(
                $this->filterDepartment && $this->filterDepartment !== 'various',
                fn($q) => $q->where('department_id', $this->filterDepartment)
            )
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function render()
    {
        $colleges = College::orderBy('name')->get();

        return view('livewire.admin.position.position-index', [
            'positions'         => $this->filteredPositions,
            'colleges'          => $colleges,
            'filterDepartments' => $this->filterCollege
                ? Department::where('college_id', $this->filterCollege)->orderBy('name')->get()
                : [],
        ]);
    }
}