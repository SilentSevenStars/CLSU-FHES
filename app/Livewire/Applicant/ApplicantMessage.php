<?php

namespace App\Livewire\Applicant;

use App\Models\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ApplicantMessage extends Component
{
    public $notificationId;
    public $notification;

    public function mount($notificationId)
    {
        $this->notificationId = $notificationId;
        
        $this->notification = Notification::whereHas('applicant', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($notificationId);

        // Mark as read when viewing
        if (!$this->notification->is_read) {
            $this->notification->markAsRead();
        }
    }

    public function back()
    {
        return redirect()->route('applicant.notifications');
    }

    public function render()
    {
        return view('livewire.applicant.applicant-message')->layout('layouts.app');
    }
}