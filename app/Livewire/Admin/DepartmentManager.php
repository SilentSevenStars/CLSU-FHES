<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\College;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;
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
    public $college_id = '';

    public string $oldName = '';
    public string $oldCollegeName = '';  

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name'       => 'required|string|max:255',
        'college_id' => 'required|exists:colleges,id',
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
        $search = trim((string) $this->search);

        $departments = Department::with('college')
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('college', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        $colleges = College::orderBy('name', 'asc')->get();

        return view('livewire.admin.department-manager', [
            'departments' => $departments,
            'colleges'    => $colleges,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->editMode  = false;
        $this->showModal = true;
    }

    public function store()
    {
        try {
            $this->validate();

            $college = College::findOrFail($this->college_id);

            Department::create([
                'name'       => $this->name,
                'college_id' => $this->college_id,
            ]);

            AccountActivityService::log(
                Auth::user(),
                "Created a new department \"{$this->name}\" under college \"{$college->name}\"."
            );

            $this->showModal = false;
            $this->resetInputFields();
            $this->dispatch('alert', type: 'success', title: 'Success!', text: 'Department created successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to create department');
        }
    }

    public function edit($id)
    {
        $department = Department::with('college')->findOrFail($id);

        $this->departmentId    = $id;
        $this->name            = $department->name;
        $this->college_id      = $department->college_id;

        $this->oldName         = $department->name;
        $this->oldCollegeName  = $department->college->name ?? '';  

        $this->editMode  = true;
        $this->showModal = true;
    }

    public function update()
    {
        try {
            $this->validate([
                'name'       => 'required|string|max:255|unique:departments,name,' . $this->departmentId,
                'college_id' => 'required|exists:colleges,id',
            ]);

            $department = Department::findOrFail($this->departmentId);
            $department->update([
                'name'       => $this->name,
                'college_id' => $this->college_id,
            ]);

            $newCollegeName = College::find($this->college_id)?->name ?? '';
            $changes        = [];

            if ($this->oldName !== $this->name) {
                $changes[] = "name: \"{$this->oldName}\" → \"{$this->name}\"";
            }

            if ($this->oldCollegeName !== $newCollegeName) {
                $changes[] = "college: \"{$this->oldCollegeName}\" → \"{$newCollegeName}\"";
            }

            if (!empty($changes)) {
                AccountActivityService::log(
                    Auth::user(),
                    "Updated department (ID: {$this->departmentId}) — " . implode(', ', $changes) . "."
                );
            }

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
            title: 'Are you sure to delete this department?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        );
    }

    #[On('destroy')]
    public function delete($id)
    {
        try {
            $department = Department::with('college')->findOrFail($id);

            $deletedName    = $department->name;
            $deletedCollege = $department->college->name ?? 'N/A'; 

            $department->delete();

            AccountActivityService::log(
                Auth::user(),
                "Deleted department \"{$deletedName}\" (ID: {$id}) under college \"{$deletedCollege}\"."
            );

            $this->dispatch('alert', type: 'success', title: 'Deleted!', text: 'Department deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to delete department');
        }
    }

    public function closeModal()
    {
        $this->showModal    = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name           = '';
        $this->college_id     = '';
        $this->departmentId   = null;
        $this->oldName        = '';
        $this->oldCollegeName = '';  
        $this->resetErrorBag();
    }
}