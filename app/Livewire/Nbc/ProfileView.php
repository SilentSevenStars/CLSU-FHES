<?php

namespace App\Livewire\Nbc;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileView extends Component
{
    public $nbc;
    public $nbcInfo;

    public function mount()
    {
        if (Auth::user()->role !== 'nbc') {
            abort(403, 'Unauthorized access.');
        }

        $this->nbc = Auth::user();
        $this->nbcInfo = \App\Models\NbcCommittee::where('user_id', Auth::id())->first();
    }

    public function render()
    {
        return view('livewire.nbc.profile-view');
    }
}
