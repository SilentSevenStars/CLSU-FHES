<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Department;
use App\Models\Panel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class PanelManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $filterPosition = 'all';
    public $filterCollege = 'all';
    public $filterDepartment = 'all';

    public $panelPositions = ['Head', 'Dean', 'Senior'];

    public $name, $email, $password, $password_confirmation;
    public $panel_position, $college_id, $department_id;
    public $colleges = [];      // Add this to make colleges accessible in modals
    public $departments = [];
    public $panelId;

    public $showCreateModal = false;
    public $showEditModal = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|same:password_confirmation',
        'panel_position' => 'required|string',
        'college_id' => 'required|exists:colleges,id',
        'department_id' => 'required|exists:departments,id',
    ];

    protected $editRules = [
        'name' => 'required|string',
        'panel_position' => 'required|string',
        'college_id' => 'required|exists:colleges,id',
        'department_id' => 'required|exists:departments,id',
    ];

    public function mount()
    {
        // Load colleges when component mounts
        $this->colleges = College::orderBy('name')->get();
    }

    public function updatedCollegeId()
    {
        if ($this->panel_position === 'Dean') {
            $this->department_id = null;
            $this->departments = [];
            return;
        }

        if (!empty($this->college_id) && $this->college_id !== 'all') {
            $this->departments = Department::where('college_id', $this->college_id)
                ->orderBy('name')
                ->get();
            $this->department_id = '';
        } else {
            $this->departments = [];
            $this->department_id = '';
        }
    }

    public function updatedPanelPosition($value)
    {
        if ($value === 'Dean') {
            $this->department_id = null;
            $this->departments = [];
        } else {
            if ($this->department_id === null) {
                $this->department_id = '';
            }

            if (!empty($this->college_id)) {
                $this->departments = Department::where('college_id', $this->college_id)
                    ->orderBy('name')
                    ->get();
            }
        }
    }

    public function updatedFilterCollege()
    {
        $this->filterDepartment = 'all';
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->colleges = College::orderBy('name')->get();  // Reload colleges
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $panel = Panel::with(['user', 'college', 'department'])->findOrFail($id);

        $this->panelId = $id;
        $this->name = $panel->user->name;
        $this->email = $panel->user->email;
        $this->panel_position = $panel->panel_position;
        
        // Load colleges
        $this->colleges = College::orderBy('name')->get();
        
        $this->college_id = (string) $panel->college_id;
        $this->department_id = (string) $panel->department_id;

        if ($this->panel_position === 'Dean') {
            $this->departments = [];
            $this->department_id = null;
        } else {
            if ($this->college_id) {
                $this->departments = Department::where('college_id', $this->college_id)
                    ->orderBy('name')
                    ->get();
            }
        }

        $this->showEditModal = true;
    }

    public function store()
    {
        // Debug: Check values before validation
        \Log::info('Store method called', [
            'college_id' => $this->college_id,
            'department_id' => $this->department_id,
            'panel_position' => $this->panel_position
        ]);

        // Adjust validation for Dean position
        $rules = $this->rules;
        if ($this->panel_position === 'Dean') {
            $rules['department_id'] = 'nullable';
        }
        
        $this->validate($rules);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => 'panel',
            'password' => Hash::make($this->password),
        ]);

        // Create panel with foreign keys
        Panel::create([
            'user_id' => $user->id,
            'panel_position' => $this->panel_position,
            'college_id' => $this->college_id,
            'department_id' => $this->department_id,
        ]);

        $this->resetForm();
        $this->showCreateModal = false;
        session()->flash('success', 'Panel created successfully!');
    }

    public function update()
    {
        $rules = $this->editRules;
        if ($this->panel_position === 'Dean') {
            $rules['department_id'] = 'nullable';
        }
        
        $this->validate($rules);

        $panel = Panel::findOrFail($this->panelId);

        $panel->user->update([
            'name' => $this->name,
        ]);

        $panel->update([
            'panel_position' => $this->panel_position,
            'college_id' => $this->college_id,
            'department_id' => $this->department_id,
        ]);

        $this->resetForm();
        $this->showEditModal = false;
        session()->flash('success', 'Panel updated successfully!');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', id: $id);
    }

    protected $listeners = ['deleteConfirmed'];

    public function deleteConfirmed($id)
    {
        $panel = Panel::findOrFail($id);

        User::where('id', $panel->user_id)->delete();
        $panel->delete();

        session()->flash('success', 'Panel deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'panel_position',
            'college_id',
            'department_id',
            'departments'
        ]);
        
        // Reload colleges after reset
        $this->colleges = College::orderBy('name')->get();
    }

    public function render()
    {
        $colleges = College::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        if ($this->filterCollege !== 'all') {
            $departments = Department::where('college_id', $this->filterCollege)
                ->orderBy('name')
                ->get();
        }

        $positions = Panel::with(['user', 'college', 'department'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                })
                ->orWhere('panel_position', 'like', "%{$this->search}%")
                ->orWhereHas('department', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterPosition !== 'all', function ($query) {
                $query->where('panel_position', $this->filterPosition);
            })
            ->when($this->filterCollege !== 'all', function ($query) {
                $query->where('college_id', $this->filterCollege);
            })
            ->when($this->filterDepartment !== 'all', function ($query) {
                $query->where('department_id', $this->filterDepartment);
            })
            ->paginate($this->perPage);

        return view('livewire.admin.panel-manager', [
            'positions' => $positions,
            'colleges' => $colleges,
            'departments' => $departments
        ]);
    }
}