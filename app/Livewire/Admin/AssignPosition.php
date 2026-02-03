<?php

namespace App\Livewire\Admin;

use App\Models\Applicant;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Mail\NotificationMail;
use Exception;
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
    
    // Archive functionality
    public $showArchiveModal = false;
    public $selectedJobApplication = null;
    public $showArchived = false;

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
            'jobApplication.position',
            'panelAssignments',
            'nbcAssignments'
        ])->findOrFail($evaluationId);

        // Check if either panel assignment or NBC assignment is complete
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

        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->reset(['selectedApplicant', 'selectedEvaluation']);
    }

    public function confirmAssignment()
    {
        Log::info('confirmAssignment called', [
            'selectedApplicant' => $this->selectedApplicant ? $this->selectedApplicant->id : null,
            'selectedEvaluation' => $this->selectedEvaluation ? $this->selectedEvaluation->id : null
        ]);

        if (!$this->selectedApplicant || !$this->selectedEvaluation) {
            Log::error('Invalid selection in confirmAssignment');
            $this->showAlert('error', 'Invalid selection.');
            return;
        }

        // Verify status again
        $hasPanelComplete = $this->selectedEvaluation->panelAssignments()
            ->where('status', 'complete')
            ->exists();
        
        $hasNbcComplete = $this->selectedEvaluation->nbcAssignments()
            ->where('status', 'complete')
            ->exists();
        
        Log::info('Status check', [
            'has_panel_complete' => $hasPanelComplete,
            'has_nbc_complete' => $hasNbcComplete
        ]);
        
        if (!$hasPanelComplete && !$hasNbcComplete) {
            Log::warning('Status validation failed');
            $this->showAlert('error', 'Cannot assign position. Either Panel Assignment or NBC Assignment must be marked as complete.');
            $this->closeConfirmModal();
            return;
        }

        DB::beginTransaction();

        try {
            $newPosition = $this->selectedEvaluation->jobApplication->position->name;
            $oldPosition = $this->selectedApplicant->position ?? 'None';

            Log::info('Starting position assignment', [
                'applicant_id' => $this->selectedApplicant->id,
                'old_position' => $oldPosition,
                'new_position' => $newPosition,
                'job_application_id' => $this->selectedEvaluation->jobApplication->id
            ]);

            // Update applicant position and set hired to true
            $this->selectedApplicant->update([
                'position' => $newPosition,
                'hired' => true,
            ]);

            Log::info('Applicant updated successfully');

            // Update job application status to hired AND archive it
            $this->selectedEvaluation->jobApplication->update([
                'status' => 'hired',
                'archive' => true, // Archive the job application after assignment
            ]);

            Log::info('Job application updated successfully (hired and archived)');

            // Send email notification
            $this->sendPromotionEmail($this->selectedApplicant, $oldPosition, $newPosition);

            DB::commit();

            Log::info('Transaction committed successfully');

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

    // Archive functionality methods
    public function openArchiveModal($jobApplicationId)
    {
        $this->selectedJobApplication = \App\Models\JobApplication::with(['applicant.user', 'position'])
            ->findOrFail($jobApplicationId);
        $this->showArchiveModal = true;
    }

    public function closeArchiveModal()
    {
        $this->showArchiveModal = false;
        $this->reset(['selectedJobApplication']);
    }

    public function confirmArchive()
    {
        if (!$this->selectedJobApplication) {
            $this->showAlert('error', 'Invalid job application selection.');
            return;
        }

        try {
            Log::info('Archiving job application', [
                'job_application_id' => $this->selectedJobApplication->id,
                'applicant_name' => $this->selectedJobApplication->applicant->user->name,
                'position' => $this->selectedJobApplication->position->name
            ]);

            // Update archive column to true
            $this->selectedJobApplication->update([
                'archive' => true,
            ]);

            Log::info('Job application archived successfully');

            $this->showAlert('success', "Successfully archived job application for {$this->selectedJobApplication->applicant->user->name}");

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
        $jobApplication = \App\Models\JobApplication::findOrFail($jobApplicationId);

        try {
            Log::info('Unarchiving job application', [
                'job_application_id' => $jobApplication->id,
                'applicant_name' => $jobApplication->applicant->user->name,
                'position' => $jobApplication->position->name
            ]);

            // Update archive column to false
            $jobApplication->update([
                'archive' => false,
            ]);

            Log::info('Job application unarchived successfully');

            $this->showAlert('success', "Successfully unarchived job application for {$jobApplication->applicant->user->name}");

            $this->resetPage();
        } catch (Exception $e) {
            Log::error('Error in unarchive: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $this->showAlert('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    protected function sendPromotionEmail($applicant, $oldPosition, $newPosition)
    {
        try {
            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found");
                return;
            }

            Log::info('Preparing promotion email', [
                'applicant_email' => $applicant->user->email,
                'old_position' => $oldPosition,
                'new_position' => $newPosition
            ]);

            $messageContent = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2 style='color: #0D7A2F;'>Congratulations on Your New Position!</h2>
                    <p>Dear {$applicant->first_name} {$applicant->last_name},</p>
                    <p>We are pleased to inform you that you have been officially <strong style='color: #0D7A2F;'>HIRED/PROMOTED</strong> to the position of <strong>{$newPosition}</strong>.</p>
                    
                    <div style='background-color: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h3 style='color: #0D7A2F; margin-top: 0;'>Position Details:</h3>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='padding: 8px 0;'><strong>Previous Position:</strong></td>
                                <td style='padding: 8px 0;'>{$oldPosition}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0;'><strong>New Position:</strong></td>
                                <td style='padding: 8px 0;'><strong style='color: #0D7A2F;'>{$newPosition}</strong></td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0;'><strong>Effective Date:</strong></td>
                                <td style='padding: 8px 0;'>" . now()->format('F j, Y') . "</td>
                            </tr>
                        </table>
                    </div>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ul style='line-height: 1.8;'>
                        <li>Please visit the HR Department to complete your onboarding documents</li>
                        <li>Bring valid government-issued IDs and other required documents</li>
                        <li>Attend the orientation session (details will follow)</li>
                    </ul>
                    
                    <p><strong>Required Documents:</strong></p>
                    <ul style='line-height: 1.8;'>
                        <li>NBI Clearance</li>
                        <li>Medical Certificate</li>
                        <li>Birth Certificate (PSA)</li>
                        <li>TOR and Diploma</li>
                        <li>2x2 ID Pictures (4 copies)</li>
                    </ul>
                    
                    <p>We look forward to your contributions to Central Luzon State University. Congratulations once again!</p>
                    
                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>CLSU HR Department</strong></p>
                </div>
            ";

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject' => "Hired/Promoted to {$newPosition}",
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

            Log::info("Promotion email sent successfully to {$applicant->user->email}");
        } catch (Exception $e) {
            Log::error("Failed to send promotion email: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    protected function showAlert($type, $message)
    {
        $this->alertType = $type;
        $this->alertMessage = $message;
        $this->showAlertModal = true;
    }

    public function closeAlertModal()
    {
        $this->showAlertModal = false;
        $this->reset(['alertType', 'alertMessage']);
    }

    public function render()
    {
        $applicantsQuery = Applicant::query()
            ->with([
                'user',
                'jobApplications' => function ($q) {
                    $q->with([
                        'position',
                        'evaluation.panelAssignments',
                        'evaluation.nbcAssignments'
                    ])
                    // Include or exclude archived job applications based on showArchived
                    ->where('archive', $this->showArchived ? true : false)
                    // Only show job applications where the position is different from current position
                    // OR applicant is not hired yet
                    ->where(function($query) {
                        $query->whereHas('applicant', function($a) {
                            // Not hired yet
                            $a->where('hired', false);
                        })
                        ->orWhere(function($subQuery) {
                            // OR hired but applying for different position (promotion)
                            $subQuery->whereHas('applicant', function($a) {
                                $a->where('hired', true);
                            })
                            ->whereRaw('(SELECT position FROM applicants WHERE id = job_applications.applicant_id) != (SELECT name FROM positions WHERE id = job_applications.position_id)');
                        });
                    });
                }
            ])
            // Must have a job application WITH evaluation and matching archive status
            ->whereHas('jobApplications', function ($q) {
                $q->where('archive', $this->showArchived ? true : false)
                    ->whereHas('evaluation', function ($e) {
                        // Either panel assignment OR NBC assignment must be complete
                        $e->where(function($query) {
                            $query->whereHas('panelAssignments', function ($panel) {
                                $panel->where('status', 'complete');
                            })
                            ->orWhereHas('nbcAssignments', function ($nbc) {
                                $nbc->where('status', 'complete');
                            });
                        });
                    })
                    // Only show job applications where position is different from current OR not hired
                    ->where(function($query) {
                        $query->whereHas('applicant', function($a) {
                            $a->where('hired', false);
                        })
                        ->orWhere(function($subQuery) {
                            $subQuery->whereHas('applicant', function($a) {
                                $a->where('hired', true);
                            })
                            ->whereRaw('(SELECT position FROM applicants WHERE id = job_applications.applicant_id) != (SELECT name FROM positions WHERE id = job_applications.position_id)');
                        });
                    });
            });

        // Search by user name
        if (!empty($this->search)) {
            $applicantsQuery->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by applied position
        if (!empty($this->positionFilter)) {
            $applicantsQuery->whereHas('jobApplications', function ($q) {
                $q->where('archive', $this->showArchived ? true : false)
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