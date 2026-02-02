<?php

namespace App\Livewire\Admin;

use App\Models\User;
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
    public $showDeleteModal = false;
    public $deleteUserId;
    public $perPage = 10;
    public $search = '';

    protected $paginationTheme = 'tailwind';

    // Roles that must NOT appear in the create/edit dropdown.
    // These roles have their own dedicated account-creation flows
    // (e.g. applicant self-registers, panel/nbc are created via their own managers).
    protected array $excludedRoles = ['applicant', 'nbc', 'panel'];

    public function rules()
    {
        // Dynamically build allowed role names for validation
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

    // -------------------------------------------------------
    // MODAL OPENERS
    // -------------------------------------------------------
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
        // Pull the first role the user currently has via Spatie
        $this->role     = $user->roles->first()?->name ?? '';
        $this->isEditMode = true;
        $this->showModal  = true;
    }

    public function openDeleteModal($id)
    {
        $this->deleteUserId = $id;
        $this->showDeleteModal = true;
    }

    // -------------------------------------------------------
    // MODAL CLOSERS
    // -------------------------------------------------------
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteUserId = null;
    }

    // -------------------------------------------------------
    // FORM RESET
    // -------------------------------------------------------
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

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------
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
            session()->flash('success', 'User deleted successfully!');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // RENDER
    // -------------------------------------------------------
    public function render()
    {
        // Only list users who hold at least one role that is NOT excluded.
        // This keeps applicant/nbc/panel users out of this management table —
        // they are managed through their own dedicated pages.
        $users = User::whereHas('roles', function ($query) {
                    $query->whereNotIn('name', $this->excludedRoles);
                })
                ->where('id', '!=', Auth::id())
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);

        // Roles available for the create/edit dropdown
        $availableRoles = Role::whereNotIn('name', $this->excludedRoles)
                               ->orderBy('name')
                               ->get();

        return view('livewire.admin.user-management', [
            'users'          => $users,
            'availableRoles' => $availableRoles,
        ]);
    }
}