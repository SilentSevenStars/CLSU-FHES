<?php

namespace App\Livewire\Admin;

use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JobApplication;

class ArchiveUserManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;

    public $showRestoreModal = false;
    public $showDeleteModal = false;
    public $selectedJobApplicationId = null;

    protected $queryString = [
        'search'  => ['except' => ''],
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
        // Load with all relations needed for the activity log
        $jobApplication = JobApplication::with(['applicant.user', 'position'])->findOrFail($this->selectedJobApplicationId);

        if ($jobApplication->status === 'hired') {
            session()->flash('error', 'Cannot restore this application. It was used to hire/promote this applicant and must be kept for records.');
            $this->closeRestoreModal();
            return;
        }

        // Capture details before the update for use in the activity log
        $applicantName = $jobApplication->applicant->user->name ?? 'Unknown';
        $positionName  = $jobApplication->position->name        ?? 'Unknown';

        $jobApplication->update(['archive' => false]);

        // ── Activity log ──────────────────────────────────────────────────────
        AccountActivityService::log(
            Auth::user(),
            "Restored archived job application for \"{$applicantName}\" — Position: \"{$positionName}\"."
        );
        // ─────────────────────────────────────────────────────────────────────

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
        // Load with all relations needed for the activity log
        $jobApplication = JobApplication::with(['applicant.user', 'position'])->findOrFail($this->selectedJobApplicationId);

        if ($jobApplication->status === 'hired') {
            session()->flash('error', 'Cannot delete this application. It was used to hire/promote this applicant and must be kept for records.');
            $this->closeDeleteModal();
            return;
        }

        // Capture details BEFORE delete — relations are gone after the record is destroyed
        $applicantName = $jobApplication->applicant->user->name ?? 'Unknown';
        $positionName  = $jobApplication->position->name        ?? 'Unknown';

        $jobApplication->delete();

        // ── Activity log ──────────────────────────────────────────────────────
        AccountActivityService::log(
            Auth::user(),
            "Permanently deleted archived job application for \"{$applicantName}\" — Position: \"{$positionName}\"."
        );
        // ─────────────────────────────────────────────────────────────────────

        session()->flash('success', 'Applicant permanently deleted.');
        $this->closeDeleteModal();
    }

    public function render()
    {
        // Base query — archive/hired filters applied at DB level (not encrypted)
        $query = JobApplication::query()
            ->with(['applicant.user', 'position'])
            ->where('archive', true)
            ->where('status', '!=', 'hired')
            ->latest();

        // If no search, paginate at DB level for performance
        if (!$this->search) {
            $archivedApplicants = $query->paginate($this->perPage);
        } else {
            // Pull all results first so Eloquent casts can decrypt name/email
            $search = strtolower($this->search);

            $all = $query->get()->filter(function ($application) use ($search) {
                // user->name and user->email are auto-decrypted by the Encrypted cast
                $name     = strtolower($application->applicant?->user?->name  ?? '');
                $email    = strtolower($application->applicant?->user?->email ?? '');
                $position = strtolower($application->position?->name          ?? '');

                return str_contains($name, $search)
                    || str_contains($email, $search)
                    || str_contains($position, $search);
            })->values();

            // Manual pagination on the filtered collection
            $perPage     = (int) $this->perPage;
            $currentPage = $this->getPage();

            $archivedApplicants = new \Illuminate\Pagination\LengthAwarePaginator(
                $all->forPage($currentPage, $perPage),
                $all->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return view('livewire.admin.archive-applicant-management', [
            'archivedApplicants' => $archivedApplicants,
        ]);
    }
}