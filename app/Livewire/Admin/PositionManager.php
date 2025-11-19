<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Department;
use App\Models\Position;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class PositionManager extends Component
{
    use WithPagination;

    public string $name = "";
    public string $college = "";
    public $colleges = [];
    public $departments = [];
    public string $department = "";
    public $start_date;
    public $end_date;
    public $position_id = null;
    public string $status = "";

    public bool $showCreateModal = false;
    public bool $showEditModal = false;

    public string $search = '';
    public string $filter = 'all';
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

    public function resetInput()
    {
        $this->name = "";
        $this->department = "";
        $this->start_date = null;
        $this->end_date = null;
        $this->position_id = null;
    }

    public function openCreateModal()
    {
        $this->resetInput();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $position = Position::findOrFail($id);

        $this->position_id = $position->id;
        $this->name = $position->name;
        $this->college = trim($position->college);
        $this->department = $position->department;
        $this->status = $position->status;
        $this->start_date = $position->start_date;
        $this->end_date = $position->end_date;

        $this->departments = Department::where('college', $this->college)->get();

        $this->showEditModal = true;
    }


    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'college' => 'required|string',
            'department' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        DB::beginTransaction();
        try {
            $position = new Position();
            $position->name = $this->name;
            $position->college = $this->college;
            $position->department = $this->department;
            $position->status = $this->status ?: 'vacant';
            $position->start_date = $this->start_date;
            $position->end_date = $this->end_date;
            $position->save();

            DB::commit();

            $this->resetInput();
            $this->dispatch('refreshPositions');
            $this->closeModal();

            $this->dispatch('alert', type: 'success', title: 'Position', text: 'Position has been created successfully', position: 'center');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', title: 'Position', text: 'Failed to create position', position: 'center');
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'college' => 'required|string',
            'department' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        DB::beginTransaction();
        try {
            $position = Position::findOrFail($this->position_id);
            $position->name = $this->name;
            $position->college = $this->college;
            $position->department = $this->department;
            $position->status = $this->status;
            $position->start_date = $this->start_date;
            $position->end_date = $this->end_date;
            $position->save();

            DB::commit();
            $this->resetInput();
            $this->dispatch('refreshPositions');
            $this->closeModal();

            $this->dispatch('alert', type: 'success', title: 'Position', text: 'Position has been updated successfully', position: 'center');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', title: 'Position', text: 'Failed to update position', position: 'center');
        }
    }

    public function updatedCollege($value)
    {
        if ($value) {
            $this->departments = Department::where('college', $value)->orderBy('name')->get();
        } else {
            $this->departments = [];
            $this->department = "";
        }
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
            $this->dispatch('alert', type: 'success', title: 'Position', text: 'Position has been deleted successfully', position: 'center');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', title: 'Position', text: 'Failed to delete position', position: 'center');
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
        return Position::query()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('department', 'like', "%{$this->search}%")
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

    public function mount()
    {
        $this->colleges = College::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.admin.position-manager', [
            'positions' => $this->filteredPositions,
            'vacant' => $this->vacantCount,
            'promotion' => $this->promotionCount,
        ])->layout('layouts.app');
    }
}
