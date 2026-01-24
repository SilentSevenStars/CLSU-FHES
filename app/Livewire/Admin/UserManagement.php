<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

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

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user_id)
            ],
            'password' => $this->isEditMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'role' => 'required|in:admin,applicant,panel,nbc',
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
        $user = User::findOrFail($id);

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deleteUserId = $id;
        $this->showDeleteModal = true;
    }

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

    public function resetForm()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'applicant';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $user = User::findOrFail($this->user_id);

                $userData = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'role' => $this->role,
                ];

                // Only include password if it's provided (model will hash it automatically)
                if ($this->password) {
                    $userData['password'] = $this->password;
                }

                $user->update($userData);

                session()->flash('success', 'User updated successfully!');
            } else {
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'role' => $this->role,
                    'password' => $this->password,
                    'email_verified_at' => now(),
                ]);

                session()->flash('success', 'User created successfully!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $user = User::findOrFail($this->deleteUserId);

            // Prevent deleting yourself
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

    public function render()
    {
        $users = User::where('role', 'admin')
            ->where('id', '!=', Auth::id()) // 👈 exclude current user
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }
}
