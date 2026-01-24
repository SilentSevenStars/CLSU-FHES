<?php

namespace App\Livewire\Panel;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileView extends Component
{
    public $panel;
    public $panelInfo;

    public function mount()
    {
        if (Auth::user()->role !== 'panel') {
            abort(403, 'Unauthorized access.');
        }

        $this->panel = Auth::user();
        // Get panel information from panels table
        $this->panelInfo = \App\Models\Panel::where('user_id', Auth::id())->first();
    }

    public function render()
    {
        return view('livewire.panel.profile-view');
    }
}

