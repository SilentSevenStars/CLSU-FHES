<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Models\Department;
use App\Models\Panel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    public $colleges = [];
    public $departments = [];
    public $panelId;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $panelToDelete = null;

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
        // Check if user has permission to view panels
        if (!Auth::user()->can('panel.view')) {
            session()->flash('error', 'You do not have permission to access this page.');
            return redirect()->route('admin.dashboard');
        }

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
        // Check permission
        if (!Auth::user()->can('panel.create')) {
            session()->flash('error', 'You do not have permission to create panels.');
            return;
        }

        $this->resetForm();
        $this->colleges = College::orderBy('name')->get();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $panel = Panel::with(['user', 'college', 'department'])->findOrFail($id);
        
        // Check permission
        if (!Auth::user()->can('panel.edit')) {
            session()->flash('error', 'You do not have permission to edit panels.');
            return;
        }

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
        // Check permission
        if (!Auth::user()->can('panel.create')) {
            session()->flash('error', 'You do not have permission to create panels.');
            return;
        }

        // Adjust validation for Dean position
        $rules = $this->rules;
        if ($this->panel_position === 'Dean') {
            $rules['department_id'] = 'nullable';
        }
        
        $this->validate($rules);

        try {
            // Create user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Assign 'panel' role (NOT panel-head, panel-dean, or panel-senior)
            $user->assignRole('panel');

            // Create panel with foreign keys
            // The panel_position field is just for organizational purposes
            Panel::create([
                'user_id' => $user->id,
                'panel_position' => $this->panel_position,
                'college_id' => $this->college_id,
                'department_id' => $this->department_id,
            ]);

            $this->resetForm();
            $this->showCreateModal = false;
            session()->flash('success', 'Panel created successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create panel: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $panel = Panel::findOrFail($this->panelId);
        
        // Check permission
        if (!Auth::user()->can('panel.edit')) {
            session()->flash('error', 'You do not have permission to edit panels.');
            $this->showEditModal = false;
            return;
        }

        $rules = $this->editRules;
        if ($this->panel_position === 'Dean') {
            $rules['department_id'] = 'nullable';
        }
        
        $this->validate($rules);

        try {
            // Update user name
            $panel->user->update([
                'name' => $this->name,
            ]);

            // Update panel
            // Note: We don't change roles because all panels have 'panel' role
            // Only the panel_position field changes for organizational purposes
            $panel->update([
                'panel_position' => $this->panel_position,
                'college_id' => $this->college_id,
                'department_id' => $this->department_id,
            ]);

            $this->resetForm();
            $this->showEditModal = false;
            session()->flash('success', 'Panel updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update panel: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        // Check permission
        if (!Auth::user()->can('panel.delete')) {
            session()->flash('error', 'You do not have permission to delete panels.');
            return;
        }

        $this->panelToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deletePanel()
    {
        // Check permission
        if (!Auth::user()->can('panel.delete')) {
            session()->flash('error', 'You do not have permission to delete panels.');
            $this->showDeleteModal = false;
            return;
        }

        try {
            $panel = Panel::findOrFail($this->panelToDelete);

            // Delete user (this will cascade delete the panel due to foreign key)
            User::where('id', $panel->user_id)->delete();
            $panel->delete();

            $this->showDeleteModal = false;
            $this->panelToDelete = null;
            session()->flash('success', 'Panel deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete panel: ' . $e->getMessage());
            $this->showDeleteModal = false;
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->panelToDelete = null;
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