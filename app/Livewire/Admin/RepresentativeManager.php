<?php

namespace App\Livewire\Admin;

use App\Models\Representative;
use Livewire\Component;
use Livewire\WithPagination;

class RepresentativeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public $name, $position;
    public $representativeId;
    
    // Predefined positions for the footer
    public $availablePositions = [
        'Member, Supervising Admin. Officer, HRMO',
        'Member, FAI President/Representative',
        'Member, GLUTCHES President',
        'Member, Ranking Faculty',
        'Dean, CASS',
        'Dean, CEN Representative',
        'Dean, COS',
        'Dean, CED',
        'Dean, CF',
        'Dean, CBA',
        'Senior Faculty',
        'Head, Dept DABE, Representative',
        'Head, Dept Business',
        'Head, ISPELS',
        'Chairman, Faculty Selection Board & VPAA',
        'University President'
    ];

    public $showCreateModal = false;
    public $showEditModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
    ];

    protected $listeners = ['deleteConfirmed'];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($id)
    {
        $representative = Representative::findOrFail($id);

        $this->representativeId = $id;
        $this->name = $representative->name;
        $this->position = $representative->position;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function store()
    {
        $this->validate();

        Representative::create([
            'name' => $this->name,
            'position' => $this->position,
        ]);

        $this->closeCreateModal();
        
        $this->js("
            window.dispatchEvent(new CustomEvent('show-alert', { 
                detail: { type: 'success', message: 'Representative created successfully!' }
            }));
        ");
    }

    public function update()
    {
        $this->validate();

        $representative = Representative::findOrFail($this->representativeId);

        $representative->update([
            'name' => $this->name,
            'position' => $this->position,
        ]);

        $this->closeEditModal();
        
        $this->js("
            window.dispatchEvent(new CustomEvent('show-alert', { 
                detail: { type: 'success', message: 'Representative updated successfully!' }
            }));
        ");
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', id: $id);
    }

    public function deleteConfirmed($id)
    {
        $representative = Representative::findOrFail($id);
        $representative->delete();

        $this->js("
            window.dispatchEvent(new CustomEvent('show-alert', { 
                detail: { type: 'success', message: 'Representative deleted successfully!' }
            }));
        ");
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'position',
            'representativeId'
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        $representatives = Representative::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('position', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.representative-manager', [
            'representatives' => $representatives
        ]);
    }
}