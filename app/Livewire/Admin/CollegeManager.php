<?php

namespace App\Livewire\Admin;

use App\Models\College;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CollegeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showModal = false;
    public $editMode = false;
    public $collegeId;
    public $name = '';

    public string $oldName = ''; 

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:255',
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
        $colleges = College::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.college-manager', [
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

            College::create([
                'name' => $this->name,
            ]);

            AccountActivityService::log(
                Auth::user(),
                "Created a new college \"{$this->name}\"."
            );

            $this->showModal = false;
            $this->resetInputFields();
            $this->dispatch('alert', type: 'success', title: 'Success!', text: 'College created successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to create college');
        }
    }

    public function edit($id)
    {
        $college = College::findOrFail($id);
        $this->collegeId = $id;
        $this->name = $college->name;
        $this->oldName = $college->name;  
        $this->editMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:colleges,name,' . $this->collegeId,
            ]);

            $college = College::findOrFail($this->collegeId);
            $college->update([
                'name' => $this->name,
            ]);

            if ($this->oldName !== $this->name) {
                AccountActivityService::log(
                    Auth::user(),
                    "Updated college (ID: {$this->collegeId}) — "
                        . "name: \"{$this->oldName}\" → \"{$this->name}\"."
                );
            }

            $this->showModal = false;
            $this->resetInputFields();
            $this->dispatch('alert', type: 'success', title: 'Success!', text: 'College updated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to update college');
        }
    }

    public function confirmDelete($id)
    {
        $this->collegeId = $id;
        $this->dispatch('confirmation',
            id: $id,
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        );
    }

    public function delete()
    {
        try {
            $college = College::findOrFail($this->collegeId);

            $deletedName = $college->name;  

            $college->delete();

            AccountActivityService::log(
                Auth::user(),
                "Deleted college \"{$deletedName}\" (ID: {$this->collegeId})."
            );

            $this->dispatch('alert', type: 'success', title: 'Deleted!', text: 'College deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to delete college');
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
        $this->collegeId = null;
        $this->oldName = '';  
        $this->resetErrorBag();
    }
}