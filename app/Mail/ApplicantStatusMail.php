<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantStatusMail extends Mailable
{
    use SerializesModels;

    public string $applicantName;
    public string $action;
    public string $position;

    public function __construct(
        string $applicantName,
        string $action,
        string $position
    ) {
        $this->applicantName = $applicantName;
        $this->action = $action;
        $this->position = $position;
    }

    public function build(): self
    {
        return $this->subject('Application Status Update')
            ->view('emails.applicant-status')
            ->with([
                'subject' => 'Application Status Update',
                'applicantName' => $this->applicantName,
                'notificationMessage' =>
                    "<p>You have been <strong>{$this->action}</strong> to the position of <strong>{$this->position}</strong>.</p>"
            ]);
    }
}
