<?php

namespace App\Livewire\Applicant;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.applicant.dashboard')
            ->layout('layouts.app');
    }
}
