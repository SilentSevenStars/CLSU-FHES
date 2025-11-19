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
    public $filter = 'all';

    // Form fields
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
        $college = trim($this->college ?? '');
        if (!empty($college)) {
            $this->departments = Department::whereRaw('lower(college) = ?', [strtolower($college)])->get();
            if (count($this->departments) === 0) {
                $this->departments = Department::where('college', 'like', "%{$college}%")->get();
            }
            $this->department = '';
        } else {
            $this->departments = [];
            $this->department = '';
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
        // Dispatch browser event for SweetAlert
        $this->dispatch('swal:confirm', id: $id);
    }

    // Listener for deleteConfirmed
    protected $listeners = ['deleteConfirmed'];

    public function deleteConfirmed($id)
    {
        $panel = Panel::findOrFail($id);

        // Delete associated user first
        User::where('id', $panel->user_id)->delete();

        // Delete panel
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

        $positions = Panel::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->paginate($this->perPage);

        return view('livewire.admin.panel-manager', [
            'positions' => $positions,
            'colleges' => $colleges
        ])->layout('layouts.app');
    }
}
