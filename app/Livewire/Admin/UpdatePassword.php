<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed',
    ];

    protected $messages = [
        'current_password.required' => 'Current password is required.',
        'password.required' => 'New password is required.',
        'password.min' => 'New password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
    ];

    public function mount()
    {
        
    }

    public function updatePassword()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->update([
            'password' => $this->password,
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('success', 'Password updated successfully!');
        $this->dispatch('password-updated');
    }

    public function render()
    {
        return view('livewire.admin.update-password');
    }
}
