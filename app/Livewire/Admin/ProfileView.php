<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileView extends Component
{
    public $admin;

    public function mount()
    {
        $this->admin = Auth::user();
    }

    public function render()
    {
        return view('livewire.admin.profile-view');
    }
}
