<?php

namespace App\Livewire\Admin;

use App\Models\Applicant;
use App\Models\Notification;
use App\Mail\NotificationMail;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class Message extends Component
{
    use WithFileUploads;

    public $applicantIds = [];
    public $subject = '';
    public $message = '';
    public $attachments = [];

    protected $rules = [
        'subject'        => 'required|string|max:255',
        'message'        => 'required|string',
        'attachments.*'  => 'nullable|file|max:10240',
    ];

    public function mount()
    {
        $applicants = request()->query('applicants', '');
        $this->applicantIds = !empty($applicants) ? explode(',', $applicants) : [];

        if (empty($this->applicantIds)) {
            session()->flash('error', 'No applicants selected.');
            return redirect()->route('admin.notifications');
        }
    }

    public function removeAttachment($index)
    {
        array_splice($this->attachments, $index, 1);
    }

    public function send()
    {
        $this->validate();

        try {
            $uploadedFilePaths = [];
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $path = $file->store('notifications/attachments', 'public');
                    $uploadedFilePaths[] = $path;
                }
            }

            $applicants = Applicant::with('user')->whereIn('id', $this->applicantIds)->get();

            $sentCount   = 0;
            $failedCount = 0;
            $recipients  = [];  

            foreach ($applicants as $applicant) {
                $notification = Notification::create([
                    'applicant_id' => $applicant->id,
                    'subject'      => $this->subject,
                    'message'      => (string) $this->message,
                    'attachments'  => $uploadedFilePaths,
                    'is_read'      => false,
                    'email_sent'   => false,
                ]);

                try {
                    Mail::to($applicant->user->email)
                        ->send(new \App\Mail\NotificationMail($notification));

                    $notification->update([
                        'email_sent'    => true,
                        'email_sent_at' => now(),
                    ]);

                    $sentCount++;
                    $recipients[] = trim("{$applicant->first_name} {$applicant->last_name}") 
                                    . " ({$applicant->user->email})";  
                } catch (\Exception $e) {
                    Log::error('Failed to send email to ' . $applicant->user->email . ': ' . $e->getMessage());
                    $failedCount++;
                }
            }

            if ($sentCount > 0) {
                $recipientList = implode(', ', $recipients);
                $attachmentNote = !empty($uploadedFilePaths)
                    ? ' with ' . count($uploadedFilePaths) . ' attachment(s)'
                    : '';

                AccountActivityService::log(
                    Auth::user(),
                    "Sent notification\"{$this->subject}\"{$attachmentNote} to {$sentCount} applicant(s): {$recipientList}."
                        . ($failedCount > 0 ? " ({$failedCount} failed to deliver.)" : '')
                );
            }

            session()->flash('success', 'Message sent successfully to ' . $applicants->count() . ' applicant(s).');
            return redirect()->route('admin.notifications');
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage());
            session()->flash('error', 'Failed to send message.');
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.notifications');
    }

    public function render()
    {
        $applicants = Applicant::with('user')->whereIn('id', $this->applicantIds)->get();

        return view('livewire.admin.message', [
            'applicants' => $applicants,
        ]);
    }
}