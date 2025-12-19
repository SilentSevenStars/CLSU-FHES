<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use App\Models\Notification;
use App\Mail\NotificationMail;
use Exception;
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
    public $originalStatus;

    public function mount($job_application_id)
    {
        $this->application = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->findOrFail($job_application_id);

        // Only allow editing if status is 'approve' or 'decline'
        if (!in_array($this->application->status, ['approve', 'decline'])) {
            session()->flash('error', 'You can only edit applications that have been approved or declined.');
            return $this->redirect(route('admin.applicant'));
        }

        // Store original status to detect changes
        $this->originalStatus = $this->application->status;

        // Set current values
        $this->status = $this->application->status;

        if ($this->status === 'approve' && $this->application->evaluation) {
            $this->interview_date = optional($this->application->evaluation->interview_date)
                ->format('Y-m-d');
            $this->interview_room ??= $this->application->evaluation->interview_room;
        }

        Log::info('ApplicantEdit mounted', [
            'application_id' => $this->application->id,
            'current_status' => $this->application->status,
            'original_status' => $this->originalStatus
        ]);
    }

    public function updateReview()
    {
        Log::info('updateReview called', [
            'new_status' => $this->status,
            'original_status' => $this->originalStatus,
            'interview_date' => $this->interview_date,
            'interview_room' => $this->interview_room
        ]);

        $this->validate([
            'status' => 'required|in:approve,decline',
            'interview_date' => $this->status === 'approve' ? 'required|date|after_or_equal:today' : 'nullable',
            'interview_room' => $this->status === 'approve' ? 'required|string|max:255' : 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $statusChanged = $this->originalStatus !== $this->status;

            // ✅ Update Job Application using ORM
            $this->application->update([
                'status' => $this->status,
                'reviewed_at' => now(),
            ]);

            /**
             * ==========================
             * APPROVE STATUS
             * ==========================
             */
            if ($this->status === 'approve') {

                // ✅ Create or update evaluation using ORM
                $this->application->evaluation()->updateOrCreate(
                    ['job_application_id' => $this->application->id],
                    [
                        'interview_date' => $this->interview_date,
                        'interview_room' => $this->interview_room,
                        'total_score' => $this->application->evaluation->total_score ?? 0,
                        'rank' => $this->application->evaluation->rank ?? null,
                    ]
                );

                // ✅ Send approval email ONLY if status changed
                if ($statusChanged && $this->originalStatus === 'decline') {
                    $this->sendApprovalEmail();
                }
            }

            /**
             * ==========================
             * DECLINE STATUS
             * ==========================
             */
            if ($this->status === 'decline') {

                // ✅ Delete evaluation ONLY if status changed
                if ($statusChanged && $this->originalStatus === 'approve') {
                    $this->application->evaluation()?->delete();
                    $this->sendDeclineEmail();
                }
            }

            // ✅ Sync Livewire state to avoid false email triggers
            $this->originalStatus = $this->status;
            $this->application->refresh();

            DB::commit();

            session()->flash(
                'success',
                $statusChanged
                    ? 'Application status updated successfully. Email notification has been sent.'
                    : 'Application details updated successfully.'
            );

            return $this->redirect(route('admin.applicant'));
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in updateReview', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Something went wrong.');
        }
    }


    protected function sendApprovalEmail()
    {
        try {
            $applicant = $this->application->applicant;
            $position = $this->application->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for application #{$this->application->id}");
                return;
            }

            Log::info('Preparing approval email', [
                'applicant_email' => $applicant->user->email,
                'interview_date' => $this->interview_date,
                'interview_room' => $this->interview_room
            ]);

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2 style='color: #0D7A2F;'>Great News - Application Approved!</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We are pleased to inform you that your application for the position of <strong>{$position->name}</strong> has been <strong style='color: #0D7A2F;'>APPROVED</strong>.</p>
                    
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
                    
                    <p><strong>Important Reminders:</strong></p>
                    <ul style='line-height: 1.8;'>
                        <li>Please arrive <strong>15 minutes before</strong> your scheduled interview time</li>
                        <li>Dress professionally</li>
                        <li>Bring a valid government-issued ID</li>
                    </ul>
                    
                    <p><strong>Required Documents to Bring:</strong></p>
                    <ul style='line-height: 1.8;'>
                        <li>Updated Resume/CV</li>
                        <li>Academic credentials (Transcripts, Diplomas)</li>
                        <li>Certificates of relevant training and seminars</li>
                        <li>Any other supporting documents</li>
                    </ul>
                    
                    <p>We look forward to meeting you. Good luck with your interview!</p>
                    
                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject' => "Application Status Changed to Approved - {$position->name}",
                'message' => $messageContent,
                'attachments' => null,
                'is_read' => false,
                'email_sent' => false,
            ]);

            Log::info("Notification created", ['notification_id' => $notification->id]);

            Mail::to($applicant->user->email)
                ->send(new NotificationMail($notification));

            $notification->update([
                'email_sent' => true,
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
            $position = $this->application->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for application #{$this->application->id}");
                return;
            }

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2>Application Status Update</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We regret to inform you that after reviewing your application for the position of <strong>{$position->name}</strong> at Central Luzon State University, we have decided not to proceed with your application at this time.</p>
                    <p>This decision was made after careful consideration of all candidates. We appreciate the time and effort you invested in your application.</p>
                    <p>We encourage you to apply for future positions that match your qualifications and experience.</p>
                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject' => "Application Status Changed - {$position->name}",
                'message' => $messageContent,
                'attachments' => null,
                'is_read' => false,
                'email_sent' => false,
            ]);

            Mail::to($applicant->user->email)
                ->send(new NotificationMail($notification));

            $notification->update([
                'email_sent' => true,
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
