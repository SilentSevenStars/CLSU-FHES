<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Notification $notification) {}

    public function build()
    {
        return $this->view('emails.notification')
            ->with([
                'subject' => $this->notification->subject,
                'applicantName' => $this->notification->applicant->full_name,
                'notificationMessage' => $this->notification->message, // renamed
            ])
            ->subject($this->notification->subject);
    }
}
