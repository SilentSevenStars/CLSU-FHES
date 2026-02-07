<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use App\Models\Notification;
use App\Mail\NotificationMail;
use App\Services\FileEncryptionService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ApplicantShow extends Component
{
    public $application;
    public $status;
    public $interview_date;
    public $interview_room;

    public function mount($job_application_id)
    {
        $this->application = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->findOrFail($job_application_id);

        Log::info('ApplicantShow mounted', [
            'application_id' => $this->application->id,
            'current_status' => $this->application->status
        ]);
    }

    public function getCanReviewProperty()
    {
        $today = now()->toDateString();
        $position = $this->application->position;

        if (!$position->start_date || !$position->end_date) {
            return false;
        }

        return $today > $position->end_date;
    }

    public function getIsWithinApplicationPeriodProperty()
    {
        $today = now()->toDateString();
        $position = $this->application->position;

        if (!$position->start_date || !$position->end_date) {
            return false;
        }

        return $today >= $position->start_date && $today <= $position->end_date;
    }

    /**
     * Generate base64 encoded PDF for viewing in modal
     */
    public function getFileDataUrl()
    {
        $encryptionService = new FileEncryptionService();

        // Check if file exists
        if (!$this->application->requirements_file || 
            !$encryptionService->fileExists($this->application->requirements_file)) {
            $this->dispatch('show-error', message: 'File not found.');
            return null;
        }

        try {
            // Decrypt file
            $decryptedContents = $encryptionService->decryptFile($this->application->requirements_file);
            
            // Convert to base64
            $base64 = base64_encode($decryptedContents);
            
            // Return data URL
            return 'data:application/pdf;base64,' . $base64;
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Error loading file: ' . $e->getMessage());
            return null;
        }
    }

    public function submitReview()
    {
        Log::info('submitReview called', [
            'status' => $this->status,
            'interview_date' => $this->interview_date,
            'interview_room' => $this->interview_room,
            'application_id' => $this->application->id
        ]);

        // Backend protection
        if ($this->isWithinApplicationPeriod) {
            Log::warning('Attempted review during application period');
            session()->flash('error', 'You cannot review the application while the application period is still ongoing.');
            return;
        }

        $this->validate([
            'status' => 'required|in:approve,decline',
            'interview_date' => $this->status === 'approve' ? 'required|date|after_or_equal:today' : 'nullable',
            'interview_room' => $this->status === 'approve' ? 'required|string|max:255' : 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $newStatus = $this->status;

            Log::info('Updating application status', [
                'from' => $this->application->status,
                'to' => $newStatus
            ]);

            $this->application->update([
                'status' => $newStatus,
                'reviewed_at' => now(),
            ]);

            if ($this->status === 'approve') {
                Log::info('Creating evaluation record');

                $this->application->evaluation()->create([
                    'interview_date' => $this->interview_date,
                    'interview_room' => $this->interview_room,
                    'total_score' => 0,
                    'rank' => null,
                ]);

                $this->application->load('evaluation');
                $this->sendApprovalEmail();
            }

            if ($this->status === 'decline') {
                $this->sendDeclineEmail();
            }

            DB::commit();
            $this->application->refresh();

            Log::info('Transaction committed successfully');

            session()->flash(
                'success',
                'Application reviewed successfully. Email notification has been sent.'
            );

            return redirect()->route('admin.applicant');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error in submitReview: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
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
                    <h2 style='color: #0D7A2F;'>Congratulations!</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>Your application for the position of <strong>{$position->name}</strong> has been <strong style='color: #0D7A2F;'>APPROVED</strong>.</p>
                    
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
                'subject' => "Application Approved - Interview Scheduled for {$position->name}",
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
                    <p>Thank you for your interest in the position of <strong>{$position->name}</strong> at Central Luzon State University.</p>
                    <p>After careful consideration, we regret to inform you that we will not be moving forward with your application at this time.</p>
                    <p>We appreciate the time and effort you invested in your application. We encourage you to apply for future positions that match your qualifications.</p>
                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject' => "Application Status Update - {$position->name}",
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
        return view('livewire.admin.applicant-show', [
            'application' => $this->application,
            'canReview' => $this->canReview,
            'isWithinApplicationPeriod' => $this->isWithinApplicationPeriod,
        ]);
    }
}