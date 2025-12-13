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

    public $name, $email, $password, $password_confirmation;
    public $panel_position, $college, $department;
    public $departments = [];
    public $panelId;

    public $showCreateModal = false;
    public $showEditModal = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|same:password_confirmation',
        'panel_position' => 'required|string',
        'college' => 'required|string',
        'department' => 'required|string',
    ];

    protected $editRules = [
        'name' => 'required|string',
        'panel_position' => 'required|string',
        'college' => 'required|string',
        'department' => 'required|string',
    ];

    public function updatedCollege()
    {
        $collegeName = $this->college;

        if (!empty($collegeName) && $collegeName !== 'all') {
            $this->departments = Department::where('college', $collegeName)->get();
            $this->department = '';
        } else {
            $this->departments = [];
            $this->department = '';
        }
    }

    public function updatedFilterCollege()
    {
        if ($this->filterCollege !== 'all') {
            $this->departments = Department::where('college', $this->filterCollege)->get();
            $this->filterDepartment = 'all';
        } else {
            $this->departments = Department::all();
            $this->filterDepartment = 'all';
        }
    }

    public function setCollege($value)
    {
        $this->college = $value;
        $this->updatedCollege();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $panel = Panel::with('user')->findOrFail($id);

        $this->panelId = $id;
        $this->name = $panel->user->name;
        $this->email = $panel->user->email;
        $this->panel_position = $panel->panel_position;
        $this->college = $panel->college;
        $this->department = $panel->department;

        $this->departments = Department::where('college', $panel->college)->get();
        $this->showEditModal = true;
    }

    public function store()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => 'panel',
            'password' => $this->password,
        ]);

        Panel::create([
            'user_id' => $user->id,
            'panel_position' => $this->panel_position,
            'college' => $this->college,
            'department' => $this->department,
        ]);

        $this->resetForm();
        $this->showCreateModal = false;
        session()->flash('success', 'Panel created successfully!');
    }

    public function update()
    {
        $this->validate($this->editRules);

        $panel = Panel::findOrFail($this->panelId);

        $panel->user->update([
            'name' => $this->name,
        ]);

        $panel->update([
            'panel_position' => $this->panel_position,
            'college' => $this->college,
            'department' => $this->department,
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
            'college',
            'department',
            'departments'
        ]);
    }

    public function render()
    {
        $colleges = College::all();
        $departments = Department::all();

        if ($this->filterCollege !== 'all') {
            $departments = Department::where('college', $this->filterCollege)->get();
        }

        $positions = Panel::with('user')

            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                })
                ->orWhere('panel_position', 'like', "%{$this->search}%")
                ->orWhere('department', 'like', "%{$this->search}%");
            })

            ->when($this->filterPosition !== 'all', function ($query) {
                $query->where('panel_position', $this->filterPosition);
            })

            ->when($this->filterCollege !== 'all', function ($query) {
                $query->where('college', $this->filterCollege);
            })

            ->when($this->filterDepartment !== 'all', function ($query) {
                $query->where('department', $this->filterDepartment);
            })

            ->paginate($this->perPage);

        return view('livewire.admin.panel-manager', [
            'positions' => $positions,
            'colleges' => $colleges,
            'departments' => $departments
        ]);
    }
}
