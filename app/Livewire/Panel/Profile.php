<?php

namespace App\Livewire\Panel;

use App\Models\User;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public string $name  = "";
    public string $email = "";

    public string $oldName  = '';  // Track original values for change logging
    public string $oldEmail = '';

    protected $rules = [
        'name'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
    ];

    protected $messages = [
        'name.required'  => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.email'    => 'Please enter a valid email address.',
        'email.unique'   => 'This email is already taken.',
    ];

    public function mount()
    {
        $user = Auth::user();

        $this->name  = $user->name;
        $this->email = $user->email;

        $this->oldName  = $user->name;   // Capture originals on load
        $this->oldEmail = $user->email;
    }

    public function updateProfile()
    {
        $this->rules['email'] = 'required|email|max:255|unique:users,email,' . Auth::id();

        $this->validate();

        $user = User::find(Auth::id());
        $user->update([
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        $changes = [];

        if ($this->oldName !== $this->name) {
            $changes[] = "name: \"{$this->oldName}\" → \"{$this->name}\"";
        }

        if ($this->oldEmail !== $this->email) {
            $changes[] = "email: \"{$this->oldEmail}\" → \"{$this->email}\"";
        }

        if (!empty($changes)) {
            AccountActivityService::log(
                Auth::user(),
                'Updated profile — ' . implode(', ', $changes) . '.'
            );
        }

        // Sync old values after a successful update so a second save compares correctly
        $this->oldName  = $this->name;
        $this->oldEmail = $this->email;

        session()->flash('success', 'Profile updated successfully!');
        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.panel.profile');
    }
}