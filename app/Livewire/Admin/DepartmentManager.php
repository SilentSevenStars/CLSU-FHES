<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\College;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class DepartmentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showModal = false;
    public $editMode = false;
    public $departmentId;
    public $name = '';
    public $college = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'college' => 'required|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $departments = Department::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('college', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $colleges = College::orderBy('name', 'asc')->get();

        return view('livewire.admin.department-manager', [
            'departments' => $departments,
            'colleges' => $colleges
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function store()
    {
        try {
            $this->validate();

            Department::create([
                'name' => $this->name,
                'college' => $this->college,
            ]);

            $this->showModal = false;
            $this->resetInputFields();
            $this->dispatch('alert', type: 'success', title: 'Success!', text: 'Department created successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to create department');
        }
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $this->departmentId = $id;
        $this->name = $department->name;
        $this->college = $department->college;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:departments,name,' . $this->departmentId,
                'college' => 'required|string|max:255',
            ]);

            $department = Department::findOrFail($this->departmentId);
            $department->update([
                'name' => $this->name,
                'college' => $this->college,
            ]);

            $this->showModal = false;
            $this->resetInputFields();
            $this->dispatch('alert', type: 'success', title: 'Success!', text: 'Department updated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to update department');
        }
    }

    public function deleteConfirmed($id)
    {
        $this->dispatch(
            'confirmation',
            id: $id,
            title: "Are you sure to delete this department?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        );
    }

    #[On('destroy')]
    public function delete($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();
            $this->dispatch('alert', type: 'success', title: 'Deleted!', text: 'Department deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to delete department');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->college = '';
        $this->departmentId = null;
        $this->resetErrorBag();
    }
}