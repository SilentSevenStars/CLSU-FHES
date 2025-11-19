<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use Carbon\Carbon;
use Livewire\Component;

class Applicant extends Component
{
    public function render()
    {
        $now = Carbon::now();

        // Count for this month
        $pendingCount = JobApplication::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', 'pending')
            ->count();

        $approvedCount = JobApplication::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', 'approve')
            ->count();

        $declinedCount = JobApplication::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', 'decline')
            ->count();

        // Show only applicants that are still pending
        $applications = JobApplication::with(['applicant.user', 'position'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.applicant', [
            'applications' => $applications,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'declinedCount' => $declinedCount,
        ])->layout('layouts.app');
    }
}
