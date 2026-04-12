<?php

namespace App\Livewire\Admin;

use App\Models\Applicant;
use App\Models\Notification;
use App\Mail\NotificationMail;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Message extends Component
{
    use WithFileUploads;

    public $applicantIds = [];
    public $subject      = '';
    public $message      = '';
    public $attachments  = [];

    protected $rules = [
        'subject'       => 'required|string|max:255',
        'message'       => 'required|string',
        'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,ppt,pptx,txt,zip',
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
            // Store files and collect metadata for email rendering + activity log
            $storedFiles = [];
            foreach ($this->attachments as $file) {
                $path = $file->store('notifications/attachments', 'local');
                $storedFiles[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }

            $applicants  = Applicant::with('user')->whereIn('id', $this->applicantIds)->get();
            $sentCount   = 0;
            $failedCount = 0;
            $recipients  = [];

            foreach ($applicants as $applicant) {
                $notification = Notification::create([
                    'applicant_id' => $applicant->id,
                    'subject'      => $this->subject,
                    'message'      => (string) $this->message,
                    // Store file metadata so downloads work from notification pages
                    'attachments'  => !empty($storedFiles)
                        ? json_encode($storedFiles)
                        : null,
                    'is_read'      => false,
                    'email_sent'   => false,
                ]);

                try {
                    // Build the mailable, pass file metadata for the email body list
                    $mailable                = new NotificationMail($notification);
                    $mailable->attachedFiles = $storedFiles;

                    // Physically attach each file to the email
                    foreach ($storedFiles as $file) {
                        $absolutePath = Storage::disk('local')->path($file['path']);
                        if (Storage::disk('local')->exists($file['path'])) {
                            $mailable->attach($absolutePath, ['as' => $file['name']]);
                        }
                    }

                    Mail::to($applicant->user->email)->queue($mailable);

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
                $recipientList  = implode(', ', $recipients);
                $attachmentNote = !empty($storedFiles)
                    ? ' with ' . count($storedFiles) . ' attachment(s)'
                    : '';

                AccountActivityService::log(
                    Auth::user(),
                    "Sent notification \"{$this->subject}\"{$attachmentNote} to {$sentCount} applicant(s): {$recipientList}."
                        . ($failedCount > 0 ? " ({$failedCount} failed to deliver.)" : '')
                );
            }

            session()->flash('success', 'Message sent successfully to ' . $applicants->count() . ' applicant(s).');
            return redirect()->route('admin.notifications');
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage());
            session()->flash('error', 'Failed to send message: ' . $e->getMessage());
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