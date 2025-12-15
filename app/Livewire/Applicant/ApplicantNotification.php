<?php

namespace App\Livewire\Applicant;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ApplicantNotification extends Component
{
    use WithPagination;

    public $filter = 'all'; 
    public $selectedNotification = null; // 🔥 ADDED

    public function viewMessage($notificationId)
    {
        return redirect()->route('applicant.message', $notificationId);
    }

    // 🔥 ADDED — Opens the modal
    public function viewNotification($notificationId)
    {
        $this->selectedNotification = Notification::whereHas('applicant', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($notificationId);

        if (!$this->selectedNotification->is_read) {
            $this->selectedNotification->markAsRead();
        }
    }

    // 🔥 ADDED — Closes the modal
    public function closeNotification()
    {
        $this->selectedNotification = null;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::whereHas('applicant', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($notificationId);

        $notification->markAsRead();
    }

    public function markAsUnread($notificationId)
    {
        $notification = Notification::whereHas('applicant', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($notificationId);

        $notification->markAsUnread();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::whereHas('applicant', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($notificationId);

        $notification->delete();
        session()->flash('success', 'Notification deleted successfully.');
    }

    public function markAllAsRead()
    {
        Notification::whereHas('applicant', function ($query) {
            $query->where('user_id', Auth::id());
        })->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        session()->flash('success', 'All notifications marked as read.');
    }

    public function render()
    {
        $applicant = Auth::user()->applicant;

        $query = Notification::where('applicant_id', $applicant->id)
            ->when($this->filter === 'read', function ($q) {
                $q->where('is_read', true);
            })
            ->when($this->filter === 'unread', function ($q) {
                $q->where('is_read', false);
            })
            ->orderBy('created_at', 'desc');

        $notifications = $query->paginate(10);

        return view('livewire.applicant.applicant-notification', [
            'notifications' => $notifications,
        ]);
    }
}
