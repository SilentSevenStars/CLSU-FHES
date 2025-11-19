<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function loadApplications()
    {
        // This method is called by wire:poll to refresh the applications
        // The render method will be called automatically after this
    }

    public function render()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();
        
        $applications = collect([]);
        if ($applicant) {
            $applications = $applicant->jobApplications()->with('position')->get();
        }

        return view('livewire.applicant.dashboard', [
            'applications' => $applications,
        ])->layout('layouts.app');
    }
}
