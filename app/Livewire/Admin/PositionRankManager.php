<?php

namespace App\Livewire\Admin;

use App\Models\PositionRank;
use Livewire\Component;
use Livewire\WithPagination;

class PositionRankManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showModal = false;
    public $editMode = false;
    public $positionRankId;
    public $name = '';

    protected $paginationTheme = 'bootstrap';

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
        $positionRanks = PositionRank::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.position-rank-manager', [
            'positionRanks' => $positionRanks
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

            PositionRank::create([
                'name' => $this->name,
            ]);

            $this->showModal = false;
            $this->resetInputFields();
            $this->dispatch('alert', type: 'success', title: 'Success!', text: 'Position rank created successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to create position rank');
        }
    }

    public function edit($id)
    {
        $positionRank = PositionRank::findOrFail($id);
        $this->positionRankId = $id;
        $this->name = $positionRank->name;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:position_ranks,name,' . $this->positionRankId,
            ]);

            $positionRank = PositionRank::findOrFail($this->positionRankId);
            $positionRank->update([
                'name' => $this->name,
            ]);

            $this->showModal = false;
            $this->resetInputFields();
            $this->dispatch('alert', type: 'success', title: 'Success!', text: 'Position rank updated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to update position rank');
        }
    }

    public function confirmDelete($id)
    {
        $this->positionRankId = $id;
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
            PositionRank::findOrFail($this->positionRankId)->delete();
            $this->dispatch('alert', type: 'success', title: 'Deleted!', text: 'Position rank deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', title: 'Error!', text: 'Failed to delete position rank');
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
        $this->positionRankId = null;
        $this->resetErrorBag();
    }
}