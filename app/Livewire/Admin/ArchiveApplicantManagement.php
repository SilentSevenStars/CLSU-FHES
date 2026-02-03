<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JobApplication;

class ArchiveApplicantManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;

    public $showRestoreModal = false;
    public $showDeleteModal = false;
    public $selectedJobApplicationId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    /* =======================
        Pagination Reset
    ======================== */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /* =======================
        Restore Applicant
    ======================== */
    public function openRestoreModal($id)
    {
        $this->selectedJobApplicationId = $id;
        $this->showRestoreModal = true;
    }

    public function closeRestoreModal()
    {
        $this->reset(['showRestoreModal', 'selectedJobApplicationId']);
    }

    public function restore()
    {
        JobApplication::where('id', $this->selectedJobApplicationId)
            ->update(['archive' => false]);

        session()->flash('success', 'Applicant restored successfully.');

        $this->closeRestoreModal();
    }

    /* =======================
        Permanent Delete
    ======================== */
    public function openDeleteModal($id)
    {
        $this->selectedJobApplicationId = $id;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->reset(['showDeleteModal', 'selectedJobApplicationId']);
    }

    public function delete()
    {
        JobApplication::findOrFail($this->selectedJobApplicationId)->delete();

        session()->flash('success', 'Applicant permanently deleted.');

        $this->closeDeleteModal();
    }

    /* =======================
        Render
    ======================== */
    public function render()
    {
        $archivedApplicants = JobApplication::query()
            ->with(['applicant.user', 'position'])
            ->where('archive', true)
            ->whereHas('applicant', function ($q) {
                $q->where('hired', false);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('applicant.user', function ($u) {
                    $u->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.archive-applicant-management', [
            'archivedApplicants' => $archivedApplicants,
        ]);
    }
}
