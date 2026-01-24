<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileView extends Component
{
    public $admin;

    public function mount()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $this->admin = Auth::user();
    }

    public function render()
    {
        return view('livewire.admin.profile-view');
    }
}
