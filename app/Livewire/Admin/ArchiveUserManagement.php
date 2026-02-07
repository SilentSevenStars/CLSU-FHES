<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ArchiveUserManagement extends Component
{
    use WithPagination;

    public $restoreUserId;
    public $deleteUserId;
    public $showRestoreModal = false;
    public $showDeleteModal = false;
    public $perPage = 10;
    public $search = '';
    public $filterRole = 'all';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function openRestoreModal($id)
    {
        $this->restoreUserId = $id;
        $this->showRestoreModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deleteUserId = $id;
        $this->showDeleteModal = true;
    }

    public function closeRestoreModal()
    {
        $this->showRestoreModal = false;
        $this->restoreUserId = null;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteUserId = null;
    }

    public function restore()
    {
        try {
            $user = User::findOrFail($this->restoreUserId);

            if ($user->id === Auth::id()) {
                session()->flash('error', 'You cannot restore your own account from here!');
                $this->closeRestoreModal();
                return;
            }

            // Set archive to false to restore the user
            $user->update(['archive' => false]);
            
            session()->flash('success', 'User restored successfully!');
            $this->closeRestoreModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $user = User::findOrFail($this->deleteUserId);

            if ($user->id === Auth::id()) {
                session()->flash('error', 'You cannot delete your own account!');
                $this->closeDeleteModal();
                return;
            }

            // Permanently delete the user
            $user->delete();
            
            session()->flash('success', 'User deleted permanently!');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = User::with(['roles', 'panel.college', 'panel.department', 'nbcCommittee', 'applicant'])
            ->where('id', '!=', Auth::id())
            ->where('archive', true);

        // Filter by role if not 'all'
        if ($this->filterRole !== 'all') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->filterRole);
            });
        }

        // Search functionality
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhereHas('applicant', function ($subQ) {
                      $subQ->where('first_name', 'like', '%' . $this->search . '%')
                           ->orWhere('last_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $archivedUsers = $query->orderBy('updated_at', 'desc')->paginate($this->perPage);

        // Get statistics counts for archived users
        $baseQuery = User::where('id', '!=', Auth::id())->where('archive', true);
        
        $totalArchived = $baseQuery->count();
        $archivedAdminCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count();
        $archivedSuperAdminCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->count();
        $archivedPanelCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'panel'))->count();
        $archivedNbcCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'nbc'))->count();
        $archivedApplicantCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'applicant'))->count();

        return view('livewire.admin.archive-user-management', [
            'archivedUsers' => $archivedUsers,
            'totalArchived' => $totalArchived,
            'archivedAdminCount' => $archivedAdminCount,
            'archivedSuperAdminCount' => $archivedSuperAdminCount,
            'archivedPanelCount' => $archivedPanelCount,
            'archivedNbcCount' => $archivedNbcCount,
            'archivedApplicantCount' => $archivedApplicantCount,
        ]);
    }
}