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
use Livewire\Component;

class ApplicantEdit extends Component
{
    public $application;
    public $status;
    public $interview_date;
    public $interview_room;
    public $admin_message;

    public $originalStatus;
    public $originalInterviewDate;
    public $originalInterviewRoom;

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

        Log::info('ApplicantEdit mounted', [
            'application_id'  => $this->application->id,
            'current_status'  => $this->application->status,
            'original_status' => $this->originalStatus,
            'interview_date'  => $this->interview_date,
            'interview_room'  => $this->interview_room,
        ]);
    }


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

    public function updateReview()
    {
        Log::info('updateReview called', [
            'new_status'               => $this->status,
            'original_status'          => $this->originalStatus,
            'new_interview_date'       => $this->interview_date,
            'original_interview_date'  => $this->originalInterviewDate,
            'new_interview_room'       => $this->interview_room,
            'original_interview_room'  => $this->originalInterviewRoom,
        ]);

        $this->validate([
            'status'         => 'required|in:approve,decline',
            'interview_date' => $this->status === 'approve' ? 'required|date' : 'nullable',
            'interview_room' => $this->status === 'approve' ? 'required|string|max:255' : 'nullable',
            'admin_message'  => 'nullable|string',
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

            $statusChanged       = $this->originalStatus !== $this->status;
            $interviewDateChanged = ($this->status === 'approve') &&
                                    ($this->originalInterviewDate !== $this->interview_date);
            $interviewRoomChanged = ($this->status === 'approve') &&
                                    (trim($this->originalInterviewRoom ?? '') !== trim($this->interview_room ?? ''));
            $hasAdminMessage     = !empty(strip_tags($this->admin_message ?? ''));

            Log::info('Change detection', [
                'statusChanged'       => $statusChanged,
                'interviewDateChanged' => $interviewDateChanged,
                'interviewRoomChanged' => $interviewRoomChanged,
            ]);

            $this->application->update([
                'status'      => $this->status,
                'reviewed_at' => now(),
            ]);

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
                    Log::info('Sending approval email due to status change');
                    $this->sendApprovalEmail();
                } elseif (!$statusChanged && ($interviewDateChanged || $interviewRoomChanged)) {
                    Log::info('Sending interview update email');
                    $this->sendInterviewUpdateEmail($interviewDateChanged, $interviewRoomChanged);
                } elseif (!$statusChanged && !$interviewDateChanged && !$interviewRoomChanged && $hasAdminMessage) {
                    Log::info('Sending interview update email due to admin message only');
                    $this->sendInterviewUpdateEmail(false, false);
                }
            }

            if ($this->status === 'decline') {
                if ($statusChanged && $this->originalStatus === 'approve') {
                    Log::info('Deleting evaluation and sending decline email');
                    $this->application->evaluation()?->delete();
                    $this->sendDeclineEmail();
                } elseif (!$statusChanged && $hasAdminMessage) {
                    Log::info('Sending decline update email due to admin message only');
                    $this->sendDeclineEmail();
                }
            }

            $changes = [];

            if ($statusChanged) {
                $oldLabel = ucfirst($this->originalStatus) . 'd';
                $newLabel = ucfirst($this->status) . 'd';
                $changes[] = "status: {$oldLabel} → {$newLabel}";
            }

            if ($interviewDateChanged) {
                $changes[] = "interview date: {$this->originalInterviewDate} → {$this->interview_date}";
            }

            if ($interviewRoomChanged) {
                $changes[] = "interview room: \"{$this->originalInterviewRoom}\" → \"{$this->interview_room}\"";
            }

            if ($hasAdminMessage) {
                $changes[] = "admin message was included";
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
            } elseif ($interviewDateChanged || $interviewRoomChanged || $hasAdminMessage) {
                session()->flash('success', 'Interview details updated successfully. Email notification has been sent.');
            } else {
                session()->flash('success', 'Application details updated successfully.');
            }

            return redirect()->route('admin.applicant');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in updateReview', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    protected function buildAdminMessageBlock(): string
    {
        if (empty(strip_tags($this->admin_message ?? ''))) {
            return '';
        }

        return "<div style='margin: 16px 0;'>" . $this->admin_message . "</div>";
    }

    protected function sendInterviewUpdateEmail($dateChanged, $roomChanged)
    {
        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for application #{$this->application->id}");
                return;
            }

            if ($dateChanged && $roomChanged) {
                $changesText = 'interview date and location';
            } elseif ($dateChanged) {
                $changesText = 'interview date';
            } elseif ($roomChanged) {
                $changesText = 'interview location';
            } else {
                $changesText = 'interview details';
            }

            Log::info('Preparing interview update email', [
                'applicant_email' => $applicant->user->email,
                'changes'         => $changesText
            ]);

            $adminMessageBlock = $this->buildAdminMessageBlock();

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

                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject'      => "Interview Details Updated - {$position->name}",
                'message'      => $messageContent,
                'attachments'  => null,
                'is_read'      => false,
                'email_sent'   => false,
            ]);

            Log::info("Notification created", ['notification_id' => $notification->id]);

            Mail::to($applicant->user->email)
                ->send(new NotificationMail($notification));

            $notification->update([
                'email_sent'    => true,
                'email_sent_at' => now(),
            ]);

            Log::info("Interview update email sent successfully to {$applicant->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send interview update email: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    protected function sendApprovalEmail()
    {
        try {
            $applicant = $this->application->applicant;
            $position  = $this->application->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for application #{$this->application->id}");
                return;
            }

            Log::info('Preparing approval email', [
                'applicant_email' => $applicant->user->email,
                'interview_date'  => $this->interview_date,
                'interview_room'  => $this->interview_room
            ]);

            $adminMessageBlock = $this->buildAdminMessageBlock();

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2 style='color: #0D7A2F;'>Application Status Updated to Approved</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We are pleased to inform you that your application status for the position of <strong>{$position->name}</strong> has been updated to <strong style='color: #0D7A2F;'>APPROVED</strong>.</p>

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

                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject'      => "Application Status Updated to Approved - {$position->name}",
                'message'      => $messageContent,
                'attachments'  => null,
                'is_read'      => false,
                'email_sent'   => false,
            ]);

            Log::info("Notification created", ['notification_id' => $notification->id]);

            Mail::to($applicant->user->email)
                ->send(new NotificationMail($notification));

            $notification->update([
                'email_sent'    => true,
                'email_sent_at' => now(),
            ]);

            Log::info("Approval email sent successfully to {$applicant->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send approval email: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    protected function sendDeclineEmail()
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
                <div style='font-family: Arial, sans-serif;'>
                    <h2>Application Status Updated to Declined</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We regret to inform you that your application status for the position of <strong>{$position->name}</strong> at Central Luzon State University has been updated to <strong>DECLINED</strong>.</p>
                    <p>This decision was made after careful consideration of all candidates. We appreciate the time and effort you invested in your application.</p>

                    {$adminMessageBlock}

                    <p>We encourage you to apply for future positions that match your qualifications and experience.</p>
                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject'      => "Application Status Updated to Declined - {$position->name}",
                'message'      => $messageContent,
                'attachments'  => null,
                'is_read'      => false,
                'email_sent'   => false,
            ]);

            Mail::to($applicant->user->email)
                ->send(new NotificationMail($notification));

            $notification->update([
                'email_sent'    => true,
                'email_sent_at' => now(),
            ]);

            Log::info("Decline email sent successfully to {$applicant->user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send decline email: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.applicant-edit', [
            'application' => $this->application,
        ]);
    }
}