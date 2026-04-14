<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use App\Models\Notification;
use App\Mail\NotificationMail;
use App\Services\AccountActivityService;
use App\Services\FileEncryptionService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicantShow extends Component
{
    use WithFileUploads;

    public $application;
    public $status;
    public $interview_date;
    public $interview_room;
    public $admin_message;

    /**
     * Livewire temp-uploaded files.
     *
     * The #[Validate] attribute is intentionally removed here.
     * Per-upload validation is handled by updatedAttachments() below,
     * which Livewire 3 calls automatically on every wire:model round-trip.
     */
    public array $attachments = [];

    // ─── Allowed file extensions (single source of truth) ────────────────────

    private const ALLOWED_MIMES = [
        'pdf', 'doc', 'docx', 'xls', 'xlsx',
        'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'txt', 'zip',
    ];

    public function mount($job_application_id)
    {
        $this->application = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->findOrFail($job_application_id);

        Log::info('ApplicantShow mounted', [
            'application_id' => $this->application->id,
            'current_status' => $this->application->status,
        ]);
    }

    // ─── Per-upload validation ────────────────────────────────────────────────

    public function updatedAttachments(): void
    {
        if (empty($this->attachments)) {
            return;
        }

        $this->validate([
            'attachments.*' => [
                'nullable', 'file', 'max:10240',
                'mimes:' . implode(',', self::ALLOWED_MIMES),
            ],
        ]);
    }

    // ─── Computed properties ──────────────────────────────────────────────────

    public function getCanReviewProperty()
    {
        $today    = now()->toDateString();
        $position = $this->application->position;

        if (!$position->start_date || !$position->end_date) {
            return false;
        }

        return $today > $position->end_date;
    }

    public function getIsWithinApplicationPeriodProperty()
    {
        $today    = now()->toDateString();
        $position = $this->application->position;

        if (!$position->start_date || !$position->end_date) {
            return false;
        }

        return $today >= $position->start_date && $today <= $position->end_date;
    }

    // ─── PDF viewer ───────────────────────────────────────────────────────────

    public function getFileDataUrl()
    {
        $encryptionService = new FileEncryptionService();

        if (!$this->application->requirements_file ||
            !$encryptionService->fileExists($this->application->requirements_file)) {
            $this->dispatch('show-error', message: 'File not found.');
            return null;
        }

        try {
            $decryptedContents = $encryptionService->decryptFile($this->application->requirements_file);
            $base64            = base64_encode($decryptedContents);
            return 'data:application/pdf;base64,' . $base64;
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Error loading file: ' . $e->getMessage());
            return null;
        }
    }

    // ─── Attachment helpers ───────────────────────────────────────────────────

    public function removeAttachment(int $index): void
    {
        array_splice($this->attachments, $index, 1);
        $this->attachments = array_values($this->attachments);
    }

    /**
     * Store Livewire temp files to the local disk and return metadata.
     */
    protected function storeUploadedFiles(): array
    {
        $stored = [];

        foreach ($this->attachments as $file) {
            try {
                $path     = $file->store('review-attachments', 'local');
                $stored[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            } catch (Exception $e) {
                Log::error('Failed to store attachment: ' . $e->getMessage());
            }
        }

        return $stored;
    }

    // ─── Submit review ────────────────────────────────────────────────────────

    public function submitReview(): void
    {
        $this->validate([
            'status'         => 'required|in:approve,decline',
            'interview_date' => $this->status === 'approve' ? 'required|date|after_or_equal:today' : 'nullable',
            'interview_room' => $this->status === 'approve' ? 'required|string|max:255' : 'nullable',
            'admin_message'  => 'nullable|string',
            'attachments.*'  => [
                'nullable', 'file', 'max:10240',
                'mimes:' . implode(',', self::ALLOWED_MIMES),
            ],
        ]);

        DB::beginTransaction();

        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            $applicantName = trim(
                ($applicant->first_name  ?? '') . ' ' .
                ($applicant->middle_name ?? '') . ' ' .
                ($applicant->last_name   ?? '')
            );

            $this->application->update([
                'status'      => $this->status,
                'reviewed_at' => now(),
            ]);

            if ($this->status === 'approve') {
                $this->application->evaluation()->create([
                    'interview_date' => $this->interview_date,
                    'interview_room' => $this->interview_room,
                    'total_score'    => 0,
                    'rank'           => null,
                ]);
            }

            DB::commit();
            $this->application->refresh();

            // Store attachments after successful DB commit
            $storedFiles = $this->storeUploadedFiles();

            if ($this->status === 'approve') {
                $this->sendApprovalEmail($storedFiles);
            }
            if ($this->status === 'decline') {
                $this->sendDeclineEmail($storedFiles);
            }

            $statusLabel    = ucfirst($this->status) . 'd';
            $detail         = $this->status === 'approve'
                ? " — Interview Date: {$this->interview_date}, Room: {$this->interview_room}"
                : '';
            $attachmentNote = !empty($storedFiles)
                ? ' with ' . count($storedFiles) . ' attachment(s)'
                : '';

            AccountActivityService::log(
                Auth::user(),
                "{$statusLabel} job application of {$applicantName} (Application ID: {$this->application->id}) "
                    . "for position \"{$position->name}\"{$detail}{$attachmentNote}."
            );

            session()->flash('success', 'Application reviewed successfully. Email notification has been sent.');
            $this->redirect(route('admin.applicant'), navigate: true);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in submitReview: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // ─── Email senders ────────────────────────────────────────────────────────

    protected function sendApprovalEmail(array $storedFiles = []): void
    {
        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for application #{$this->application->id}");
                return;
            }

            $adminMessageBlock = $this->buildAdminMessageBlock();

            $messageContent = "
                <p style='margin:0 0 18px;font-size:16px;color:#374151;'>
                    Dear <strong>{$applicant->first_name} {$applicant->last_name}</strong>,
                </p>
                <p style='margin:0 0 14px;font-size:15px;color:#374151;'>
                    Your application for the position of <strong>{$position->name}</strong>
                    has been <strong style='color:#0D7A2F;'>APPROVED</strong>.
                </p>
                <div style='background-color:#f0f9ff;padding:20px;border-radius:8px;margin:20px 0;'>
                    <h3 style='color:#0D7A2F;margin:0 0 12px;font-size:15px;'>Interview Details:</h3>
                    <table style='width:100%;'>
                        <tr>
                            <td style='padding:8px 0;font-weight:600;width:100px;'>Date:</td>
                            <td style='padding:8px 0;'>" . date('F j, Y (l)', strtotime($this->interview_date)) . "</td>
                        </tr>
                        <tr>
                            <td style='padding:8px 0;font-weight:600;'>Location:</td>
                            <td style='padding:8px 0;'>{$this->interview_room}</td>
                        </tr>
                    </table>
                </div>
                {$adminMessageBlock}
                <p style='margin:24px 0 0;font-size:14px;color:#374151;'>
                    Best regards,<br><strong>CLSU HR Department</strong>
                </p>
            ";

            $this->createAndSendNotification(
                $applicant,
                "Application Approved - Interview Scheduled for {$position->name}",
                $messageContent,
                $storedFiles
            );

            Log::info("Approval email sent to {$applicant->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send approval email: " . $e->getMessage());
        }
    }

    protected function sendDeclineEmail(array $storedFiles = []): void
    {
        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for application #{$this->application->id}");
                return;
            }

            $adminMessageBlock = $this->buildAdminMessageBlock();

            $messageContent = "
                <p style='margin:0 0 18px;font-size:16px;color:#374151;'>
                    Dear <strong>{$applicant->first_name} {$applicant->last_name}</strong>,
                </p>
                <p style='margin:0 0 14px;font-size:15px;color:#374151;'>
                    Thank you for your interest in the position of <strong>{$position->name}</strong>
                    at Central Luzon State University.
                </p>
                <p style='margin:0 0 14px;font-size:15px;color:#374151;'>
                    After careful consideration, we regret to inform you that we will not be
                    moving forward with your application at this time.
                </p>
                {$adminMessageBlock}
                <p style='margin:0 0 14px;font-size:15px;color:#374151;'>
                    We appreciate the time and effort you invested in your application.
                    We encourage you to apply for future positions that match your qualifications.
                </p>
                <p style='margin:24px 0 0;font-size:14px;color:#374151;'>
                    Best regards,<br><strong>CLSU HR Department</strong>
                </p>
            ";

            $this->createAndSendNotification(
                $applicant,
                "Application Status Update - {$position->name}",
                $messageContent,
                $storedFiles
            );

            Log::info("Decline email sent to {$applicant->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send decline email: " . $e->getMessage());
        }
    }

    // ─── Shared helpers ───────────────────────────────────────────────────────

    protected function buildAdminMessageBlock(): string
    {
        if (empty(strip_tags($this->admin_message ?? ''))) {
            return '';
        }

        return "
            <div style='margin:16px 0;font-size:14px;color:#374151;'>
                {$this->admin_message}
            </div>
        ";
    }

    protected function createAndSendNotification($applicant, string $subject, string $message, array $storedFiles): void
    {
        $notification = Notification::create([
            'applicant_id' => $applicant->id,
            'subject'      => $subject,
            'message'      => $message,
            'attachments'  => !empty($storedFiles) ? $storedFiles : null,
            'is_read'      => false,
            'email_sent'   => false,
        ]);

        $mailable                = new NotificationMail($notification);
        $mailable->attachedFiles = $storedFiles;

        foreach ($storedFiles as $file) {
            $absolutePath = Storage::disk('local')->path($file['path']);
            if (Storage::disk('local')->exists($file['path'])) {
                $mailable->attach($absolutePath, ['as' => $file['name']]);
            }
        }

        Mail::to($applicant->user->email)->send($mailable);

        $notification->update([
            'email_sent'    => true,
            'email_sent_at' => now(),
        ]);
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin.applicant-show', [
            'application'               => $this->application,
            'canReview'                 => $this->canReview,
            'isWithinApplicationPeriod' => $this->isWithinApplicationPeriod,
        ]);
    }
}