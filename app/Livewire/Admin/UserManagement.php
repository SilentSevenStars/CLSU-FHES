<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    public $name, $email, $password, $password_confirmation, $role, $user_id;
    public $isEditMode = false;
    public $showModal = false;
    public $showArchiveModal = false;
    public $archiveUserId;
    public $perPage = 10;
    public $search = '';

    protected $paginationTheme = 'tailwind';
    protected array $excludedRoles = ['applicant', 'nbc', 'panel'];

    public function rules()
    {
        $allowedRoles = Role::whereNotIn('name', $this->excludedRoles)
                             ->pluck('name')
                             ->toArray();

        return [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'password' => $this->isEditMode
                ? 'nullable|min:8|confirmed'
                : 'required|min:8|confirmed',
            'role'     => ['required', Rule::in($allowedRoles)],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $user = User::with('roles')->findOrFail($id);

        $this->user_id  = $user->id;
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->role     = $user->roles->first()?->name ?? '';
        $this->isEditMode = true;
        $this->showModal  = true;
    }

    public function openArchiveModal($id)
    {
        $this->archiveUserId = $id;
        $this->showArchiveModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeArchiveModal()
    {
        $this->showArchiveModal = false;
        $this->archiveUserId = null;
    }

    public function resetForm()
    {
        $this->user_id              = null;
        $this->name                 = '';
        $this->email                = '';
        $this->password             = '';
        $this->password_confirmation = '';
        $this->role                 = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $user = User::findOrFail($this->user_id);

                $userData = [
                    'name'  => $this->name,
                    'email' => $this->email,
                    'email_verified_at' => Carbon::now()
                ];

                if ($this->password) {
                    $userData['password'] = $this->password;
                }

                $user->update($userData);

                $user->syncRoles([$this->role]);

                session()->flash('success', 'User updated successfully!');
            } else {
                $user = User::create([
                    'name'              => $this->name,
                    'email'             => $this->email,
                    'password'          => $this->password,
                    'email_verified_at' => now(),
                ]);

                // Assign the selected Spatie role
                $user->assignRole($this->role);

                session()->flash('success', 'User created successfully!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function archive()
    {
        try {
            $user = User::findOrFail($this->archiveUserId);

            if ($user->id === Auth::id()) {
                session()->flash('error', 'You cannot archive your own account!');
                $this->closeArchiveModal();
                return;
            }

            $user->update(['archive' => true]);
            
            session()->flash('success', 'User archived successfully!');
            $this->closeArchiveModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {

        $users = User::whereHas('roles', function ($query) {
                    $query->whereNotIn('name', $this->excludedRoles);
                })
                ->where('id', '!=', Auth::id())
                ->where('archive', false) // Only show non-archived users
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);

        $availableRoles = Role::whereNotIn('name', $this->excludedRoles)
                               ->orderBy('name')
                               ->get();

        return view('livewire.admin.user-management', [
            'users'          => $users,
            'availableRoles' => $availableRoles,
        ]);
    }
}