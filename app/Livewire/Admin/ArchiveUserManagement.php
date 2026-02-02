<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ArchiveUserManagement extends Component
{
    use WithPagination;

    public $restoreUserId;
    public $deleteUserId;
    public $showRestoreModal = false;
    public $showDeleteModal = false;
    public $perPage = 10;
    public $search = '';

    protected $paginationTheme = 'tailwind';

    protected array $excludedRoles = ['applicant', 'nbc', 'panel'];

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
        $archivedUsers = User::whereHas('roles', function ($query) {
                    $query->whereNotIn('name', $this->excludedRoles);
                })
                ->where('id', '!=', Auth::id())
                ->where('archive', true) 
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('updated_at', 'desc') 
                ->paginate($this->perPage);

        return view('livewire.admin.archive-user-management', [
            'archivedUsers' => $archivedUsers,
        ]);
    }
}