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

/**
 * PositionManager - Livewire Component for managing positions
 * 
 * This component handles CRUD operations for positions with college and department relationships.
 * Each position belongs to a college and a department (foreign keys: college_id, department_id).
 * 
 * Relationships:
 * - Position belongsTo College
 * - Position belongsTo Department
 * - College hasMany Positions
 * - Department hasMany Positions
 */
class PositionManager extends Component
{
    use WithPagination;

    // Form fields
    public string $name = "";
    public $college_id = "";      // Foreign key reference to colleges table
    public $colleges = [];
    public $departments = [];
    public $department_id = "";   // Foreign key reference to departments table
    public $start_date;
    public $end_date;
    public $position_id = null;
    public string $status = "";

    // Modal states
    public bool $showCreateModal = false;
    public bool $showEditModal = false;

    // Search and filter properties
    public string $search = '';
    public string $filter = 'all';
    public string $filterCollege = '';      // Filter by college_id
    public string $filterDepartment = '';   // Filter by department_id

    public int $perPage = 5;

    protected $paginationTheme = 'tailwind';

    // Validation rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'college_id' => 'required|exists:colleges,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

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
        // Reset department filter when college filter changes
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

    public function resetInput()
    {
        $this->name = "";
        $this->college_id = "";
        $this->department_id = "";
        $this->start_date = null;
        $this->end_date = null;
        $this->position_id = null;
        $this->status = "";
        $this->departments = [];
    }

    public function openCreateModal()
    {
        $this->resetInput();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $position = Position::with(['college', 'department'])->findOrFail($id);

        $this->position_id = $position->id;
        $this->name = $position->name;
        $this->status = $position->status;
        $this->start_date = $position->start_date;
        $this->end_date = $position->end_date;
        
        // IMPORTANT: Load college_id first, then departments, then department_id
        // Cast to string to match the property type declaration
        $this->college_id = (string) $position->college_id;
        
        // Load departments for the selected college
        if ($this->college_id) {
            $this->departments = Department::where('college_id', $this->college_id)
                ->orderBy('name')
                ->get();
        }
        
        // Set department_id AFTER departments are loaded
        // Cast to string to match the property type declaration
        $this->department_id = (string) $position->department_id;

        $this->showEditModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Create position with foreign keys
            Position::create([
                'name' => $this->name,
                'college_id' => $this->college_id,         // References colleges.id
                'department_id' => $this->department_id,   // References departments.id
                'status' => $this->status ?: 'vacant',
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'specialization' => '',
                'education' => '',
                'experience' => 0,
                'training' => 0,
                'eligibility' => '',
            ]);

            DB::commit();

            $this->resetInput();
            $this->dispatch('refreshPositions');
            $this->closeModal();

            $this->dispatch('alert', type: 'success', title: 'Position', text: 'Position has been created successfully', position: 'center');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', title: 'Position', text: 'Failed to create position: ' . $e->getMessage(), position: 'center');
        }
    }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $position = Position::findOrFail($this->position_id);
            
            // Update position with foreign keys
            $position->update([
                'name' => $this->name,
                'college_id' => $this->college_id,         // References colleges.id
                'department_id' => $this->department_id,   // References departments.id
                'status' => $this->status,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            DB::commit();
            $this->resetInput();
            $this->dispatch('refreshPositions');
            $this->closeModal();

            $this->dispatch('alert', type: 'success', title: 'Position', text: 'Position has been updated successfully', position: 'center');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', title: 'Position', text: 'Failed to update position: ' . $e->getMessage(), position: 'center');
        }
    }

    /**
     * When college is selected, load its departments
     * This is triggered when the college_id changes in the form
     */
    public function updatedCollegeId($value)
    {
        if ($value) {
            // Load departments for the selected college using foreign key relationship
            $this->departments = Department::where('college_id', $value)
                ->orderBy('name')
                ->get();
        } else {
            $this->departments = [];
            $this->department_id = "";
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

    /**
     * Get filtered positions using eager loading and foreign key relationships
     */
    public function getFilteredPositionsProperty()
    {
        return Position::with(['college', 'department'])  // Eager load relationships
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
                fn($q) => $q->where('college_id', $this->filterCollege)  // Filter by college_id
            )
            ->when(
                $this->filterDepartment,
                fn($q) => $q->where('department_id', $this->filterDepartment)  // Filter by department_id
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

    public function mount()
    {
        // Load all colleges for the dropdown
        $this->colleges = College::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.admin.position-manager', [
            'positions' => $this->filteredPositions,
            'vacant' => $this->vacantCount,
            'promotion' => $this->promotionCount,
            // Load departments for filter based on selected college
            'filterDepartments' => $this->filterCollege
                ? Department::where('college_id', $this->filterCollege)->orderBy('name')->get()
                : []
        ]);
    }
}