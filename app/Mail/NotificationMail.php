<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * File metadata for rendering the attachment list inside the email body.
     * Each entry: ['name' => string, 'size' => int, 'path' => string]
     *
     * @var array
     */
    public array $attachedFiles = [];

    public function __construct(public Notification $notification) {}

    public function build()
    {
        $mailable = $this->view('emails.notification')
            ->with([
                'subject'             => $this->notification->subject,
                'applicantName'       => $this->notification->applicant->full_name,
                'notificationMessage' => $this->notification->message,
                'attachedFiles'       => $this->attachedFiles,
            ])
            ->subject($this->notification->subject);

        Log::info('NotificationMail built', [
            'notification_id' => $this->notification->id,
            'attached_files_count' => count($this->attachedFiles),
            'attached_files' => $this->attachedFiles,
        ]);

        return $mailable;
    }
}