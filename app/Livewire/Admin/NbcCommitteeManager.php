<?php

namespace App\Livewire\Admin;

use App\Models\NbcCommittee;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class NbcCommitteeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showModal = false;
    public $editMode = false;
    
    public $committeeId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $position;
    
    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'position' => 'required|in:evaluator,verifier',
        ];

        if ($this->editMode) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->committeeId . ',id';
            $rules['password'] = 'nullable|min:8|confirmed';
        } else {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'position.required' => 'Please select a position.',
        'position.in' => 'Invalid position selected.',
    ];

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
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $committee = NbcCommittee::with('user')->findOrFail($id);
        
        $this->committeeId = $committee->user_id;
        $this->name = $committee->user->name;
        $this->email = $committee->user->email;
        $this->position = $committee->position;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                // Update existing user and committee
                $user = User::findOrFail($this->committeeId);
                $user->name = $this->name;
                $user->email = $this->email;
                
                // Only update password if provided
                if (!empty($this->password)) {
                    $user->password = Hash::make($this->password);
                }
                
                $user->save();

                // Update NBC committee position
                $committee = NbcCommittee::where('user_id', $user->id)->first();
                if ($committee) {
                    $committee->position = $this->position;
                    $committee->save();
                }
                
                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => 'NBC Committee member updated successfully!'
                ]);
            } else {
                // Create new user with 'nbc' role
                $user = new User();
                $user->name = $this->name;
                $user->email = $this->email;
                $user->password = Hash::make($this->password);
                $user->role = 'nbc';
                $user->save();

                // Create NBC committee record
                $committee = new NbcCommittee();
                $committee->user_id = $user->id;
                $committee->position = $this->position;
                $committee->save();
                
                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => 'NBC Committee member created successfully!'
                ]);
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function confirmDelete($id)
    {
        $this->committeeId = $id;
        $this->dispatch('confirm-delete', ['id' => $id]);
    }

    public function delete()
    {
        try {
            $committee = NbcCommittee::with('user')->findOrFail($this->committeeId);
            $user = $committee->user;
            
            $committee->delete();
            
            $user->delete();
            
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'NBC Committee member and account deleted successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    private function resetForm()
    {
        $this->committeeId = null;
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->password_confirmation = null;
        $this->position = null;
    }

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