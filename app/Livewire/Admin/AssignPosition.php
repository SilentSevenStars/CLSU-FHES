<?php

namespace App\Livewire\Admin;

use App\Models\Applicant;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Mail\NotificationMail;
use App\Services\AccountActivityService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class AssignPosition extends Component
{
    use WithPagination;

    public $search = '';
    public $positionFilter = '';
    public $perPage = 10;
    public $showConfirmModal = false;
    public $selectedApplicant = null;
    public $selectedEvaluation = null;
    public $showAlertModal = false;
    public $alertType = 'success';
    public $alertMessage = '';
    public $showSearchModal = false;
    public $searchInput = '';
    public $tempSearch = '';
    public $tempPositionFilter = '';
    public $showDropdown = false;
    public $filteredNames = [];

    // Message for hire/promote notification
    public $admin_message = '';

    // Archive functionality
    public $showArchiveModal = false;
    public $selectedJobApplication = null;
    public $showArchived = false;

    // Message for archive notification
    public $archive_message = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'positionFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
        'showArchived' => ['except' => false],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPositionFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearchInput()
    {
        Log::info('Search input updated', ['value' => $this->searchInput]);

        if (strlen($this->searchInput) >= 1) {
            $names = Applicant::query()
                ->with('user')
                ->whereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->searchInput . '%');
                })
                ->get()
                ->pluck('user.name')
                ->unique()
                ->values()
                ->toArray();

            Log::info('Filtered names', ['names' => $names]);

            $this->filteredNames = $names;
            $this->showDropdown = count($this->filteredNames) > 0;

            Log::info('Dropdown state', ['show' => $this->showDropdown, 'count' => count($this->filteredNames)]);
        } else {
            $this->filteredNames = [];
            $this->showDropdown = false;
        }
    }

    public function selectName($name)
    {
        $this->searchInput = $name;
        $this->showDropdown = false;
    }

    public function openSearchModal()
    {
        $this->showSearchModal = true;
        $this->tempSearch = $this->search;
        $this->tempPositionFilter = $this->positionFilter;
        $this->searchInput = $this->search;
    }

    public function closeSearchModal()
    {
        $this->showSearchModal = false;
        $this->reset(['searchInput', 'tempSearch', 'tempPositionFilter', 'showDropdown', 'filteredNames']);
    }

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->positionFilter = $this->tempPositionFilter;
        $this->closeSearchModal();
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'positionFilter', 'searchInput', 'tempSearch', 'tempPositionFilter']);
        $this->closeSearchModal();
        $this->resetPage();
    }

    public function getAvailablePositionsProperty()
    {
        return \App\Models\Position::orderBy('name')->pluck('name');
    }

    public function openConfirmModal($applicantId, $evaluationId)
    {
        $this->selectedApplicant = Applicant::with(['user', 'jobApplications.position'])->findOrFail($applicantId);
        $this->selectedEvaluation = Evaluation::with([
            'jobApplication.position.college',
            'jobApplication.position.department',
            'panelAssignments',
            'nbcAssignments'
        ])->findOrFail($evaluationId);

        $hasPanelComplete = $this->selectedEvaluation->panelAssignments()
            ->where('status', 'complete')
            ->exists();

        $hasNbcComplete = $this->selectedEvaluation->nbcAssignments()
            ->where('status', 'complete')
            ->exists();

        if (!$hasPanelComplete && !$hasNbcComplete) {
            $this->showAlert('error', 'Cannot assign position. Either Panel Assignment or NBC Assignment must be marked as complete.');
            $this->reset(['selectedApplicant', 'selectedEvaluation']);
            return;
        }

        $this->admin_message = '';
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->reset(['selectedApplicant', 'selectedEvaluation', 'admin_message']);
    }

    public function confirmAssignment()
    {
        Log::info('confirmAssignment called', [
            'selectedApplicant'  => $this->selectedApplicant  ? $this->selectedApplicant->id  : null,
            'selectedEvaluation' => $this->selectedEvaluation ? $this->selectedEvaluation->id : null,
        ]);

        if (!$this->selectedApplicant || !$this->selectedEvaluation) {
            Log::error('Invalid selection in confirmAssignment');
            $this->showAlert('error', 'Invalid selection.');
            return;
        }

        $hasPanelComplete = $this->selectedEvaluation->panelAssignments()
            ->where('status', 'complete')
            ->exists();

        $hasNbcComplete = $this->selectedEvaluation->nbcAssignments()
            ->where('status', 'complete')
            ->exists();

        Log::info('Status check', [
            'has_panel_complete' => $hasPanelComplete,
            'has_nbc_complete'   => $hasNbcComplete,
        ]);

        if (!$hasPanelComplete && !$hasNbcComplete) {
            Log::warning('Status validation failed');
            $this->showAlert('error', 'Cannot assign position. Either Panel Assignment or NBC Assignment must be marked as complete.');
            $this->closeConfirmModal();
            return;
        }

        DB::beginTransaction();

        try {
            $newPosition   = $this->selectedEvaluation->jobApplication->position->name;
            $oldPosition   = $this->selectedApplicant->position ?? 'None';
            $positionModel = $this->selectedEvaluation->jobApplication->position;

            Log::info('Starting position assignment', [
                'applicant_id'       => $this->selectedApplicant->id,
                'old_position'       => $oldPosition,
                'new_position'       => $newPosition,
                'job_application_id' => $this->selectedEvaluation->jobApplication->id,
            ]);

            $this->selectedApplicant->update([
                'position' => $newPosition,
                'hired'    => true,
            ]);

            Log::info('Applicant updated successfully');

            $this->selectedEvaluation->jobApplication->update([
                'status'  => 'hired',
                'archive' => true,
            ]);

            Log::info('Job application updated successfully (status: hired, archive: true)');

            $this->sendPromotionEmail($this->selectedApplicant, $oldPosition, $positionModel, $this->admin_message);

            DB::commit();

            Log::info('Transaction committed successfully');

            // ── Activity log ──────────────────────────────────────────────────
            $collegeName    = $positionModel->college->name    ?? 'Various Colleges';
            $departmentName = $positionModel->department->name ?? 'Various Departments';

            AccountActivityService::log(
                Auth::user(),
                "Assigned/promoted \"{$this->selectedApplicant->user->name}\" to position \"{$newPosition}\" "
                    . "(previously: \"{$oldPosition}\") — College: {$collegeName}, Department: {$departmentName}."
            );
            // ─────────────────────────────────────────────────────────────────

            $this->showAlert('success', "Successfully assigned {$this->selectedApplicant->user->name} to position: {$newPosition}");

            $this->closeConfirmModal();
            $this->resetPage();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error in confirmAssignment: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $this->showAlert('error', 'Something went wrong: ' . $e->getMessage());
            $this->closeConfirmModal();
        }
    }

    // -------------------------------------------------------------------------
    // Archive functionality
    // -------------------------------------------------------------------------

    public function openArchiveModal($jobApplicationId)
    {
        $jobApplication = \App\Models\JobApplication::with(['applicant.user', 'position.college', 'position.department'])
            ->findOrFail($jobApplicationId);

        if ($jobApplication->status === 'hired') {
            $this->showAlert('error', 'Cannot archive this application. It was used to hire/promote this applicant and must be kept for records.');
            return;
        }

        $this->selectedJobApplication = $jobApplication;
        $this->archive_message = '';
        $this->showArchiveModal = true;
    }

    public function closeArchiveModal()
    {
        $this->showArchiveModal = false;
        $this->reset(['selectedJobApplication', 'archive_message']);
    }

    public function confirmArchive()
    {
        if (!$this->selectedJobApplication) {
            $this->showAlert('error', 'Invalid job application selection.');
            return;
        }

        if ($this->selectedJobApplication->status === 'hired') {
            $this->showAlert('error', 'Cannot archive this application. It was used to hire/promote this applicant.');
            $this->closeArchiveModal();
            return;
        }

        try {
            Log::info('Archiving job application', [
                'job_application_id' => $this->selectedJobApplication->id,
                'applicant_name'     => $this->selectedJobApplication->applicant->user->name,
                'position'           => $this->selectedJobApplication->position->name,
            ]);

            $this->selectedJobApplication->update([
                'archive' => true,
            ]);

            Log::info('Job application archived successfully');

            // ── Activity log ──────────────────────────────────────────────────
            $applicantName  = $this->selectedJobApplication->applicant->user->name;
            $positionName   = $this->selectedJobApplication->position->name;
            $collegeName    = $this->selectedJobApplication->position->college->name    ?? 'Various Colleges';
            $departmentName = $this->selectedJobApplication->position->department->name ?? 'Various Departments';

            AccountActivityService::log(
                Auth::user(),
                "Archived job application for \"{$applicantName}\" — Position: \"{$positionName}\", "
                    . "College: {$collegeName}, Department: {$departmentName}."
            );
            // ─────────────────────────────────────────────────────────────────

            $this->sendArchiveEmail($this->selectedJobApplication, $this->archive_message);

            $this->showAlert('success', "Successfully archived job application for {$applicantName}");

            $this->closeArchiveModal();
            $this->resetPage();
        } catch (Exception $e) {
            Log::error('Error in confirmArchive: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $this->showAlert('error', 'Something went wrong: ' . $e->getMessage());
            $this->closeArchiveModal();
        }
    }

    public function unarchive($jobApplicationId)
    {
        $jobApplication = \App\Models\JobApplication::with(['applicant.user', 'position.college', 'position.department'])
            ->findOrFail($jobApplicationId);

        if ($jobApplication->status === 'hired') {
            $this->showAlert('error', 'Cannot restore this application. It was used to hire/promote this applicant and must be kept for records.');
            return;
        }

        try {
            Log::info('Unarchiving job application', [
                'job_application_id' => $jobApplication->id,
                'applicant_name'     => $jobApplication->applicant->user->name,
                'position'           => $jobApplication->position->name,
            ]);

            $jobApplication->update([
                'archive' => false,
            ]);

            Log::info('Job application unarchived successfully');

            // ── Activity log ──────────────────────────────────────────────────
            $applicantName  = $jobApplication->applicant->user->name;
            $positionName   = $jobApplication->position->name;
            $collegeName    = $jobApplication->position->college->name    ?? 'Various Colleges';
            $departmentName = $jobApplication->position->department->name ?? 'Various Departments';

            AccountActivityService::log(
                Auth::user(),
                "Unarchived job application for \"{$applicantName}\" — Position: \"{$positionName}\", "
                    . "College: {$collegeName}, Department: {$departmentName}."
            );
            // ─────────────────────────────────────────────────────────────────

            $this->showAlert('success', "Successfully unarchived job application for {$applicantName}");

            $this->resetPage();
        } catch (Exception $e) {
            Log::error('Error in unarchive: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $this->showAlert('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Email helpers
    // -------------------------------------------------------------------------

    /**
     * Send hire / promote notification email.
     *
     * @param  \App\Models\Applicant  $applicant
     * @param  string                 $oldPosition   Previous position name (or 'None')
     * @param  \App\Models\Position   $position      The newly assigned position (with college/department loaded)
     * @param  string                 $adminMessage  Rich-text HTML composed by the admin in Quill
     */
    protected function sendPromotionEmail($applicant, $oldPosition, $position, $adminMessage)
    {
        try {
            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found");
                return;
            }

            $newPositionName = $position->name;

            Log::info('Preparing promotion email', [
                'applicant_email' => $applicant->user->email,
                'old_position'    => $oldPosition,
                'new_position'    => $newPositionName,
                'college'         => $position->college?->name,
                'department'      => $position->department?->name,
            ]);

            // ── Admin-written message block ──────────────────────────────────
            $adminMessageBlock = '';
            if (!empty(strip_tags($adminMessage ?? ''))) {
                $adminMessageBlock = "<div style='margin: 16px 0;'>{$adminMessage}</div>";
            }

            // ── College / Department block (only when set on the position) ───
            $placementRows = '';

            if ($position->college) {
                $placementRows .= "
                    <tr>
                        <td style='padding: 6px 0; color: #4b5563;'><strong>College:</strong></td>
                        <td style='padding: 6px 0;'>{$position->college->name}</td>
                    </tr>";
            }

            if ($position->department) {
                $placementRows .= "
                    <tr>
                        <td style='padding: 6px 0; color: #4b5563;'><strong>Department:</strong></td>
                        <td style='padding: 6px 0;'>{$position->department->name}</td>
                    </tr>";
            }

            $placementBlock = '';
            if ($placementRows) {
                $placementBlock = "
                    <div style='background-color: #f0fdf4; padding: 16px; border-radius: 8px; margin: 16px 0; border-left: 4px solid #16a34a;'>
                        <p style='margin: 0 0 8px; font-weight: 600; color: #15803d;'>Placement Details:</p>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 6px 0; color: #4b5563;'><strong>Position:</strong></td>
                                <td style='padding: 6px 0;'>{$newPositionName}</td>
                            </tr>
                            {$placementRows}
                        </table>
                    </div>";
            }

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>

                    {$adminMessageBlock}

                    {$placementBlock}

                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject'      => "Hired/Promoted to {$newPositionName}",
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

            Log::info("Promotion email sent successfully to {$applicant->user->email}");
        } catch (Exception $e) {
            Log::error("Failed to send promotion email: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Send archive notification email.
     *
     * The position on $jobApplication must already have college/department loaded
     * (done in openArchiveModal via eager-loading).
     */
    protected function sendArchiveEmail($jobApplication, $adminMessage)
    {
        try {
            $applicant = $jobApplication->applicant;
            $position  = $jobApplication->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for archive notification");
                return;
            }

            Log::info('Preparing archive email', [
                'applicant_email' => $applicant->user->email,
                'position'        => $position->name,
                'college'         => $position->college?->name,
                'department'      => $position->department?->name,
            ]);

            // ── Admin-written message block ──────────────────────────────────
            $adminMessageBlock = '';
            if (!empty(strip_tags($adminMessage ?? ''))) {
                $adminMessageBlock = "<div style='margin: 16px 0;'>{$adminMessage}</div>";
            }

            // ── College / Department block (only when set on the position) ───
            $placementRows = '';

            if ($position->college) {
                $placementRows .= "
                    <tr>
                        <td style='padding: 6px 0; color: #4b5563;'><strong>College:</strong></td>
                        <td style='padding: 6px 0;'>{$position->college->name}</td>
                    </tr>";
            }

            if ($position->department) {
                $placementRows .= "
                    <tr>
                        <td style='padding: 6px 0; color: #4b5563;'><strong>Department:</strong></td>
                        <td style='padding: 6px 0;'>{$position->department->name}</td>
                    </tr>";
            }

            $placementBlock = '';
            if ($placementRows) {
                $placementBlock = "
                    <div style='background-color: #fefce8; padding: 16px; border-radius: 8px; margin: 16px 0; border-left: 4px solid #ca8a04;'>
                        <p style='margin: 0 0 8px; font-weight: 600; color: #92400e;'>Application Details:</p>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 6px 0; color: #4b5563;'><strong>Position:</strong></td>
                                <td style='padding: 6px 0;'>{$position->name}</td>
                            </tr>
                            {$placementRows}
                        </table>
                    </div>";
            }

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>

                    {$adminMessageBlock}

                    {$placementBlock}

                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject'      => "Job Application Update - {$position->name}",
                'message'      => $messageContent,
                'attachments'  => null,
                'is_read'      => false,
                'email_sent'   => false,
            ]);

            Log::info("Archive notification created", ['notification_id' => $notification->id]);

            Mail::to($applicant->user->email)
                ->send(new NotificationMail($notification));

            $notification->update([
                'email_sent'    => true,
                'email_sent_at' => now(),
            ]);

            Log::info("Archive email sent successfully to {$applicant->user->email}");
        } catch (Exception $e) {
            Log::error("Failed to send archive email: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    // -------------------------------------------------------------------------
    // Alert helpers
    // -------------------------------------------------------------------------

    protected function showAlert($type, $message)
    {
        $this->alertType    = $type;
        $this->alertMessage = $message;
        $this->showAlertModal = true;
    }

    public function closeAlertModal()
    {
        $this->showAlertModal = false;
        $this->reset(['alertType', 'alertMessage']);
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        $applicantsQuery = Applicant::query()
            ->with([
                'user',
                'jobApplications' => function ($q) {
                    $q->with([
                        'position.college',
                        'position.department',
                        'evaluation.panelAssignments',
                        'evaluation.nbcAssignments',
                    ])
                    ->where('archive', $this->showArchived ? true : false)
                    ->where('status', '!=', 'hired');
                },
            ])
            ->whereHas('jobApplications', function ($q) {
                $q->where('archive', $this->showArchived ? true : false)
                    ->where('status', '!=', 'hired')
                    ->whereHas('evaluation', function ($e) {
                        $e->where(function ($query) {
                            $query->whereHas('panelAssignments', function ($panel) {
                                $panel->where('status', 'complete');
                            })
                            ->orWhereHas('nbcAssignments', function ($nbc) {
                                $nbc->where('status', 'complete');
                            });
                        });
                    });
            });

        if (!empty($this->search)) {
            $applicantsQuery->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->positionFilter)) {
            $applicantsQuery->whereHas('jobApplications', function ($q) {
                $q->where('archive', $this->showArchived ? true : false)
                    ->where('status', '!=', 'hired')
                    ->whereHas('position', function ($p) {
                        $p->where('name', $this->positionFilter);
                    });
            });
        }

        return view('livewire.admin.assign-position', [
            'applicants' => $applicantsQuery
                ->latest()
                ->paginate($this->perPage),

            'availablePositions' => $this->availablePositions,
        ]);
    }
}