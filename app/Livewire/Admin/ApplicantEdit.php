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

class ApplicantEdit extends Component
{
    use WithFileUploads;

    public $application;
    public $status;
    public $interview_date;
    public $interview_room;
    public $admin_message;

    public $originalStatus;
    public $originalInterviewDate;
    public $originalInterviewRoom;

    /**
     * Livewire temp-uploaded files.
     *
     * The #[Validate] attribute is intentionally removed here.
     * Per-upload validation is handled by updatedAttachments() below,
     * which Livewire 3 calls automatically on every wire:model round-trip.
     * This avoids the 422 caused by the attribute-based validator
     * mishandling the mimetypes: rule during temporary uploads.
     */
    public array $attachments = [];

    // ─── Allowed file extensions (single source of truth) ────────────────────

    private const ALLOWED_MIMES = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'jpg',
        'jpeg',
        'png',
        'txt',
        'zip',
    ];

    public function mount($job_application_id)
    {
        $this->application = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->findOrFail($job_application_id);

        if (!in_array($this->application->status, ['approve', 'decline'])) {
            session()->flash('error', 'You can only edit applications that have been approved or declined.');
            return $this->redirect(route('admin.applicant'));
        }

        $this->originalStatus = $this->application->status;
        $this->status         = $this->application->status;

        if ($this->status === 'approve' && $this->application->evaluation) {
            $this->interview_date = $this->application->evaluation->interview_date
                ? $this->application->evaluation->interview_date->format('Y-m-d')
                : null;
            $this->interview_room = $this->application->evaluation->interview_room ?? null;
        } else {
            $this->interview_date = null;
            $this->interview_room = null;
        }

        $this->originalInterviewDate = $this->interview_date;
        $this->originalInterviewRoom = $this->interview_room;
    }

    // ─── Per-upload validation (replaces broken #[Validate] attribute) ────────

    /**
     * Called automatically by Livewire 3 whenever $attachments is updated
     * via wire:model (i.e. on every temporary file upload round-trip).
     * Returning early on failure prevents the 422.
     */
    public function updatedAttachments(): void
    {
        if (empty($this->attachments)) {
            return;
        }

        $this->validate([
            'attachments.*' => [
                'nullable',
                'file',
                'max:10240',
                'mimes:' . implode(',', self::ALLOWED_MIMES),
            ],
        ]);
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

    // ─── Update review ────────────────────────────────────────────────────────

    public function updateReview(): void
    {
        $this->validate([
            'status'         => 'required|in:approve,decline',
            'interview_date' => $this->status === 'approve' ? 'required|date' : 'nullable',
            'interview_room' => $this->status === 'approve' ? 'required|string|max:255' : 'nullable',
            'admin_message'  => 'nullable|string',
            'attachments.*'  => [
                'nullable',
                'file',
                'max:10240',
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

            $statusChanged        = $this->originalStatus !== $this->status;
            $interviewDateChanged = ($this->status === 'approve') &&
                                    ($this->originalInterviewDate !== $this->interview_date);
            $interviewRoomChanged = ($this->status === 'approve') &&
                                    (trim($this->originalInterviewRoom ?? '') !== trim($this->interview_room ?? ''));
            $hasAdminMessage      = !empty(strip_tags($this->admin_message ?? ''));
            $hasAttachments       = !empty($this->attachments);

            $this->application->update([
                'status'      => $this->status,
                'reviewed_at' => now(),
            ]);

            $storedFiles = $this->storeUploadedFiles();

            if ($this->status === 'approve') {
                $this->application->evaluation()->updateOrCreate(
                    ['job_application_id' => $this->application->id],
                    [
                        'interview_date' => $this->interview_date,
                        'interview_room' => $this->interview_room,
                        'total_score'    => $this->application->evaluation->total_score ?? 0,
                        'rank'           => $this->application->evaluation->rank ?? null,
                    ]
                );

                if ($statusChanged && $this->originalStatus === 'decline') {
                    $this->sendApprovalEmail($storedFiles);
                } elseif (!$statusChanged && ($interviewDateChanged || $interviewRoomChanged)) {
                    $this->sendInterviewUpdateEmail($interviewDateChanged, $interviewRoomChanged, $storedFiles);
                } elseif (!$statusChanged && !$interviewDateChanged && !$interviewRoomChanged && ($hasAdminMessage || $hasAttachments)) {
                    $this->sendInterviewUpdateEmail(false, false, $storedFiles);
                }
            }

            if ($this->status === 'decline') {
                if ($statusChanged && $this->originalStatus === 'approve') {
                    $this->application->evaluation()?->delete();
                    $this->sendDeclineEmail($storedFiles);
                } elseif (!$statusChanged && ($hasAdminMessage || $hasAttachments)) {
                    $this->sendDeclineEmail($storedFiles);
                }
            }

            // Activity log
            $changes = [];
            if ($statusChanged) {
                $changes[] = 'status: ' . ucfirst($this->originalStatus) . 'd → ' . ucfirst($this->status) . 'd';
            }
            if ($interviewDateChanged) {
                $changes[] = "interview date: {$this->originalInterviewDate} → {$this->interview_date}";
            }
            if ($interviewRoomChanged) {
                $changes[] = "interview room: \"{$this->originalInterviewRoom}\" → \"{$this->interview_room}\"";
            }
            if ($hasAdminMessage) {
                $changes[] = 'admin message was included';
            }
            if (!empty($storedFiles)) {
                $changes[] = count($storedFiles) . ' attachment(s) sent';
            }

            if (!empty($changes)) {
                AccountActivityService::log(
                    Auth::user(),
                    "Updated job application of {$applicantName} (Application ID: {$this->application->id}) "
                        . "for position \"{$position->name}\" — "
                        . implode('; ', $changes) . '.'
                );
            }

            $this->originalStatus        = $this->status;
            $this->originalInterviewDate = $this->interview_date;
            $this->originalInterviewRoom = $this->interview_room;
            $this->application->refresh();

            DB::commit();

            if ($statusChanged) {
                session()->flash('success', 'Application status updated successfully. Email notification has been sent.');
            } elseif ($interviewDateChanged || $interviewRoomChanged || $hasAdminMessage || $hasAttachments) {
                session()->flash('success', 'Interview details updated successfully. Email notification has been sent.');
            } else {
                session()->flash('success', 'Application details updated successfully.');
            }

            $this->redirect(route('admin.applicant'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in updateReview: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // ─── Email senders ────────────────────────────────────────────────────────

    protected function sendInterviewUpdateEmail(bool $dateChanged, bool $roomChanged, array $storedFiles = []): void
    {
        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            if (!$applicant || !$applicant->user) return;

            $changesText = match (true) {
                $dateChanged && $roomChanged => 'interview date and location',
                $dateChanged                 => 'interview date',
                $roomChanged                 => 'interview location',
                default                      => 'interview details',
            };

            $adminMessageBlock = $this->buildAdminMessageBlock();
            $attachmentBlock   = $this->buildAttachmentBlock($storedFiles);

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2 style='color: #0D7A2F;'>Interview Details Updated</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We would like to inform you that your <strong>{$changesText}</strong> for the position of <strong>{$position->name}</strong> has been updated.</p>
                    <div style='background-color: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h3 style='color: #0D7A2F; margin-top: 0;'>Updated Interview Details:</h3>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='padding: 8px 0;'><strong>Date:</strong></td>
                                <td style='padding: 8px 0;'>" . date('F j, Y (l)', strtotime($this->interview_date)) . "</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0;'><strong>Location:</strong></td>
                                <td style='padding: 8px 0;'>{$this->interview_room}</td>
                            </tr>
                        </table>
                    </div>
                    {$adminMessageBlock}
                    {$attachmentBlock}
                    <p style='margin-top: 30px;'>Best regards,<br><strong>CLSU HR Department</strong></p>
                </div>
            ";

            $this->createAndSendNotification(
                $applicant,
                "Interview Details Updated - {$position->name}",
                $messageContent,
                $storedFiles
            );
        } catch (\Exception $e) {
            Log::error("Failed to send interview update email: " . $e->getMessage());
        }
    }

    protected function sendApprovalEmail(array $storedFiles = []): void
    {
        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            if (!$applicant || !$applicant->user) return;

            $adminMessageBlock = $this->buildAdminMessageBlock();
            $attachmentBlock   = $this->buildAttachmentBlock($storedFiles);

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2 style='color: #0D7A2F;'>Application Status Updated to Approved</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We are pleased to inform you that your application for the position of <strong>{$position->name}</strong> has been updated to <strong style='color: #0D7A2F;'>APPROVED</strong>.</p>
                    <div style='background-color: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h3 style='color: #0D7A2F; margin-top: 0;'>Interview Details:</h3>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='padding: 8px 0;'><strong>Date:</strong></td>
                                <td style='padding: 8px 0;'>" . date('F j, Y (l)', strtotime($this->interview_date)) . "</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0;'><strong>Location:</strong></td>
                                <td style='padding: 8px 0;'>{$this->interview_room}</td>
                            </tr>
                        </table>
                    </div>
                    {$adminMessageBlock}
                    {$attachmentBlock}
                    <p style='margin-top: 30px;'>Best regards,<br><strong>CLSU HR Department</strong></p>
                </div>
            ";

            $this->createAndSendNotification(
                $applicant,
                "Application Status Updated to Approved - {$position->name}",
                $messageContent,
                $storedFiles
            );
        } catch (\Exception $e) {
            Log::error("Failed to send approval email: " . $e->getMessage());
        }
    }

    protected function sendDeclineEmail(array $storedFiles = []): void
    {
        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            if (!$applicant || !$applicant->user) return;

            $adminMessageBlock = $this->buildAdminMessageBlock();
            $attachmentBlock   = $this->buildAttachmentBlock($storedFiles);

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2>Application Status Updated to Declined</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We regret to inform you that your application for the position of <strong>{$position->name}</strong> has been updated to <strong>DECLINED</strong>.</p>
                    <p>This decision was made after careful consideration of all candidates. We appreciate the time and effort you invested in your application.</p>
                    {$adminMessageBlock}
                    {$attachmentBlock}
                    <p>We encourage you to apply for future positions that match your qualifications.</p>
                    <p style='margin-top: 30px;'>Best regards,<br><strong>CLSU HR Department</strong></p>
                </div>
            ";

            $this->createAndSendNotification(
                $applicant,
                "Application Status Updated to Declined - {$position->name}",
                $messageContent,
                $storedFiles
            );
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
        return "<div style='margin: 16px 0;'>{$this->admin_message}</div>";
    }

    protected function buildAttachmentBlock(array $storedFiles): string
    {
        if (empty($storedFiles)) {
            return '';
        }

        $items = '';
        foreach ($storedFiles as $file) {
            $ext  = strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION));
            $size = $file['size'] >= 1048576
                ? round($file['size'] / 1048576, 1) . ' MB'
                : round($file['size'] / 1024, 1) . ' KB';

            $items .= "
            <tr>
                <td style='padding:8px 12px;border-bottom:1px solid #e5e7eb;'>
                    <table cellpadding='0' cellspacing='0'>
                        <tr>
                            <td style='width:32px;height:32px;background:#0D7A2F;border-radius:6px;text-align:center;vertical-align:middle;'>
                                <span style='color:white;font-size:10px;font-weight:700;'>{$ext}</span>
                            </td>
                            <td style='padding-left:10px;vertical-align:middle;'>
                                <div style='font-size:14px;font-weight:600;color:#111827;'>{$file['name']}</div>
                                <div style='font-size:12px;color:#9ca3af;'>{$size}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>";
        }

        $count = count($storedFiles);
        return "
        <div style='margin:20px 0;'>
            <p style='margin:0 0 10px;font-weight:700;color:#0D7A2F;font-size:14px;'>📎 Attachments ({$count})</p>
            <table style='width:100%;border-collapse:collapse;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;'>
                {$items}
                <tr>
                    <td style='padding:8px 12px;'>
                        <span style='font-size:12px;color:#9ca3af;'>Files are attached directly to this email.</span>
                    </td>
                </tr>
            </table>
        </div>";
    }

    protected function createAndSendNotification($applicant, string $subject, string $message, array $storedFiles): void
    {
        $notification = Notification::create([
            'applicant_id' => $applicant->id,
            'subject'      => $subject,
            'message'      => $message,
            'attachments'  => !empty($storedFiles)
                ? $storedFiles
                : null,
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

        Mail::to($applicant->user->email)->queue($mailable);

        $notification->update([
            'email_sent'    => true,
            'email_sent_at' => now(),
        ]);
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin.applicant-edit', [
            'application' => $this->application,
        ]);
    }
}