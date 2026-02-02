<?php

namespace App\Livewire\Admin;

use App\Models\NbcCommittee;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class NbcCommitteeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    // Create/Edit modal
    public $showModal = false;
    public $editMode = false;
    public $committeeId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $position;

    // Delete confirmation modal
    public $showDeleteModal = false;
    public $deleteCommitteeId;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'position' => 'required|in:evaluator,verifier',
        ];

        if ($this->editMode) {
            $rules['email']    = 'required|email|unique:users,email,' . $this->committeeId . ',id';
            $rules['password'] = 'nullable|min:8|confirmed';
        } else {
            $rules['email']    = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'name.required'              => 'Name is required.',
        'email.required'             => 'Email is required.',
        'email.email'                => 'Please enter a valid email address.',
        'email.unique'               => 'This email is already registered.',
        'password.required'          => 'Password is required.',
        'password.min'               => 'Password must be at least 8 characters.',
        'password.confirmed'         => 'Password confirmation does not match.',
        'position.required'          => 'Please select a position.',
        'position.in'                => 'Invalid position selected.',
    ];

    // -------------------------------------------------------
    // PAGINATION RESETS
    // -------------------------------------------------------
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // -------------------------------------------------------
    // CREATE / EDIT MODAL
    // -------------------------------------------------------
    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode   = false;
        $this->showModal  = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $committee = NbcCommittee::with('user')->findOrFail($id);

        $this->committeeId = $committee->user_id;
        $this->name        = $committee->user->name;
        $this->email       = $committee->user->email;
        $this->position    = $committee->position;
        $this->editMode    = true;
        $this->showModal   = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // -------------------------------------------------------
    // SAVE
    // -------------------------------------------------------
    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $user = User::findOrFail($this->committeeId);

                $userData = [
                    'name'  => $this->name,
                    'email' => $this->email,
                ];

                // Only update password when a new one is provided
                if (!empty($this->password)) {
                    $userData['password'] = $this->password;
                }

                $user->update($userData);

                // Update the committee position
                $committee = NbcCommittee::where('user_id', $user->id)->first();
                if ($committee) {
                    $committee->update(['position' => $this->position]);
                }

                session()->flash('success', 'NBC Committee member updated successfully!');
            } else {
                // Create the user account
                $user = User::create([
                    'name'              => $this->name,
                    'email'             => $this->email,
                    'password'          => $this->password,
                    'email_verified_at' => now(),
                ]);

                // Assign the nbc role via Spatie
                $user->assignRole('nbc');

                // Create the NBC committee record
                NbcCommittee::create([
                    'user_id'  => $user->id,
                    'position' => $this->position,
                ]);

                session()->flash('success', 'NBC Committee member created successfully!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // DELETE CONFIRMATION MODAL
    // -------------------------------------------------------
    public function openDeleteModal($id)
    {
        $this->deleteCommitteeId  = $id;
        $this->showDeleteModal    = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal   = false;
        $this->deleteCommitteeId = null;
    }

    public function delete()
    {
        try {
            $committee = NbcCommittee::with('user')->findOrFail($this->deleteCommitteeId);
            $user      = $committee->user;

            // Delete committee record first (child), then the user (parent)
            $committee->delete();
            $user->delete();

            session()->flash('success', 'NBC Committee member and account deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }

        $this->closeDeleteModal();
    }

    // -------------------------------------------------------
    // RESET
    // -------------------------------------------------------
    private function resetForm()
    {
        $this->committeeId           = null;
        $this->name                  = null;
        $this->email                 = null;
        $this->password              = null;
        $this->password_confirmation = null;
        $this->position              = null;
    }

    // -------------------------------------------------------
    // RENDER
    // -------------------------------------------------------
    public function render()
    {
        $committees = NbcCommittee::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('position', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.nbc-committee-manager', [
            'committees' => $committees,
        ]);
    }
}