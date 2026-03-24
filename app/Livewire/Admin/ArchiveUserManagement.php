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

    public function updatingSearch()   { $this->resetPage(); }
    public function updatingPerPage()  { $this->resetPage(); }
    public function updatingFilterRole() { $this->resetPage(); }

    public function openRestoreModal($id)
    {
        $this->restoreUserId    = $id;
        $this->showRestoreModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deleteUserId    = $id;
        $this->showDeleteModal = true;
    }

    public function closeRestoreModal()
    {
        $this->showRestoreModal = false;
        $this->restoreUserId   = null;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteUserId   = null;
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

            $user->delete();
            session()->flash('success', 'User deleted permanently!');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Base DB query — role filter and archive flag applied at DB level
        $query = User::with(['roles', 'panel.college', 'panel.department', 'nbcCommittee', 'applicant'])
            ->where('id', '!=', Auth::id())
            ->where('archive', true);

        if ($this->filterRole !== 'all') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->filterRole);
            });
        }

        // Stats queries (no encryption involved)
        $baseQuery               = User::where('id', '!=', Auth::id())->where('archive', true);
        $totalArchived           = $baseQuery->count();
        $archivedAdminCount      = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count();
        $archivedSuperAdminCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->count();
        $archivedPanelCount      = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'panel'))->count();
        $archivedNbcCount        = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'nbc'))->count();
        $archivedApplicantCount  = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'applicant'))->count();

        // If no search, paginate at DB level for performance
        if (!$this->search) {
            $archivedUsers = $query->orderBy('updated_at', 'desc')->paginate($this->perPage);
        } else {
            // Pull all role-filtered users first so Eloquent casts can decrypt name/email
            $search = strtolower($this->search);

            $all = $query->orderBy('updated_at', 'desc')->get()->filter(function ($user) use ($search) {
                $roleName = $user->roles->first()?->name ?? '';

                // For applicants: search first_name, middle_name, last_name (encrypted on Applicant model)
                if ($roleName === 'applicant' && $user->applicant) {
                    $firstName  = strtolower($user->applicant->first_name ?? '');
                    $middleName = strtolower($user->applicant->middle_name ?? '');
                    $lastName   = strtolower($user->applicant->last_name ?? '');

                    if (str_contains($firstName, $search)
                        || str_contains($middleName, $search)
                        || str_contains($lastName, $search)) {
                        return true;
                    }
                }

                // For all users: search name and email (both encrypted via Encrypted cast)
                $name  = strtolower($user->name ?? '');
                $email = strtolower($user->email ?? '');

                return str_contains($name, $search) || str_contains($email, $search);
            })->values();

            // Manual pagination on the filtered collection
            $perPage     = (int) $this->perPage;
            $currentPage = $this->getPage();

            $archivedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
                $all->forPage($currentPage, $perPage),
                $all->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return view('livewire.admin.archive-user-management', [
            'archivedUsers'          => $archivedUsers,
            'totalArchived'          => $totalArchived,
            'archivedAdminCount'     => $archivedAdminCount,
            'archivedSuperAdminCount' => $archivedSuperAdminCount,
            'archivedPanelCount'     => $archivedPanelCount,
            'archivedNbcCount'       => $archivedNbcCount,
            'archivedApplicantCount' => $archivedApplicantCount,
        ]);
    }
}