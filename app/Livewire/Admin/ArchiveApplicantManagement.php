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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function openRestoreModal($id)
    {
        $jobApplication = JobApplication::with('applicant')->findOrFail($id);
        
        if ($jobApplication->status === 'hired') {
            session()->flash('error', 'Cannot restore this application. It was used to hire/promote this applicant and must be kept for records.');
            return;
        }

        $this->selectedJobApplicationId = $id;
        $this->showRestoreModal = true;
    }

    public function closeRestoreModal()
    {
        $this->reset(['showRestoreModal', 'selectedJobApplicationId']);
    }

    public function restore()
    {
        $jobApplication = JobApplication::with('applicant')->findOrFail($this->selectedJobApplicationId);
        
        if ($jobApplication->status === 'hired') {
            session()->flash('error', 'Cannot restore this application. It was used to hire/promote this applicant and must be kept for records.');
            $this->closeRestoreModal();
            return;
        }

        JobApplication::where('id', $this->selectedJobApplicationId)
            ->update(['archive' => false]);

        session()->flash('success', 'Applicant restored successfully.');

        $this->closeRestoreModal();
    }

    public function openDeleteModal($id)
    {
        $jobApplication = JobApplication::with('applicant')->findOrFail($id);
        
        if ($jobApplication->status === 'hired') {
            session()->flash('error', 'Cannot delete this application. It was used to hire/promote this applicant and must be kept for records.');
            return;
        }

        $this->selectedJobApplicationId = $id;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->reset(['showDeleteModal', 'selectedJobApplicationId']);
    }

    public function delete()
    {
        $jobApplication = JobApplication::with('applicant')->findOrFail($this->selectedJobApplicationId);
        
        if ($jobApplication->status === 'hired') {
            session()->flash('error', 'Cannot delete this application. It was used to hire/promote this applicant and must be kept for records.');
            $this->closeDeleteModal();
            return;
        }

        $jobApplication->delete();

        session()->flash('success', 'Applicant permanently deleted.');

        $this->closeDeleteModal();
    }

    public function render()
    {
        $archivedApplicants = JobApplication::query()
            ->with(['applicant.user', 'position'])
            ->where('archive', true)
            ->where('status', '!=', 'hired')
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