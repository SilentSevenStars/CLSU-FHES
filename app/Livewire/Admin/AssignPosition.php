<?php

namespace App\Livewire\Admin;

use App\Models\Applicant;
use App\Models\College;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Mail\NotificationMail;
use App\Services\AccountActivityService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class AssignPosition extends Component
{
    use WithPagination, WithFileUploads;

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

    // Confirm modal — read-only selects pre-filled from job application
    public $confirmPositionId   = null;
    public $confirmCollegeId    = null;
    public $confirmDepartmentId = null;

    // File attachments for hire/promote email
    public $attachments = [];

    // Archive functionality
    public $showArchiveModal = false;
    public $selectedJobApplication = null;
    public $showArchived = false;

    // Message for archive notification (optional)
    public $archive_message = '';

    // File attachments for archive email (optional)
    public $archiveAttachments = [];

    protected $queryString = [
        'search'         => ['except' => ''],
        'positionFilter' => ['except' => ''],
        'perPage'        => ['except' => 10],
        'showArchived'   => ['except' => false],
    ];

    // ─── Validation rules for file uploads ────────────────────────────────────
    protected function rules(): array
    {
        return [
            'attachments.*'        => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls,ppt,pptx,txt,zip',
            'archiveAttachments.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls,ppt,pptx,txt,zip',
        ];
    }

    // ─── Pagination resets ────────────────────────────────────────────────────
    public function updatingSearch()         { $this->resetPage(); }
    public function updatingPositionFilter() { $this->resetPage(); }
    public function updatingPerPage()        { $this->resetPage(); }

    // ─── Search modal autocomplete ────────────────────────────────────────────
    public function updatedSearchInput()
    {
        if (strlen($this->searchInput) >= 1) {
            $names = Applicant::query()
                ->with('user')
                ->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->searchInput . '%'))
                ->get()
                ->pluck('user.name')
                ->unique()
                ->values()
                ->toArray();

            $this->filteredNames = $names;
            $this->showDropdown  = count($this->filteredNames) > 0;
        } else {
            $this->filteredNames = [];
            $this->showDropdown  = false;
        }
    }

    public function selectName($name)
    {
        $this->searchInput  = $name;
        $this->showDropdown = false;
    }

    public function openSearchModal()
    {
        $this->showSearchModal    = true;
        $this->tempSearch         = $this->search;
        $this->tempPositionFilter = $this->positionFilter;
        $this->searchInput        = $this->search;
    }

    public function closeSearchModal()
    {
        $this->showSearchModal = false;
        $this->reset(['searchInput', 'tempSearch', 'tempPositionFilter', 'showDropdown', 'filteredNames']);
    }

    public function applySearch()
    {
        $this->search         = $this->searchInput;
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

    // ─── Computed properties ──────────────────────────────────────────────────
    public function getAvailablePositionsProperty()
    {
        return \App\Models\Position::orderBy('name')->pluck('name');
    }

    public function getCollegesProperty()
    {
        return College::orderBy('name')->get();
    }

    public function getDepartmentsForConfirmProperty()
    {
        if (!$this->confirmCollegeId) {
            return collect();
        }
        return Department::where('college_id', $this->confirmCollegeId)->orderBy('name')->get();
    }

    // ─── Confirm (Hire/Promote) modal ─────────────────────────────────────────
    public function openConfirmModal($applicantId, $evaluationId)
    {
        $this->selectedApplicant  = Applicant::with(['user', 'jobApplications.position'])->findOrFail($applicantId);
        $this->selectedEvaluation = Evaluation::with([
            'jobApplication.position.college',
            'jobApplication.position.department',
            'panelAssignments',
            'nbcAssignments',
        ])->findOrFail($evaluationId);

        $hasPanelComplete = $this->selectedEvaluation->panelAssignments()->where('status', 'complete')->exists();
        $hasNbcComplete   = $this->selectedEvaluation->nbcAssignments()->where('status', 'complete')->exists();

        if (!$hasPanelComplete && !$hasNbcComplete) {
            $this->showAlert('error', 'Cannot assign position. Either Panel Assignment or NBC Assignment must be marked as complete.');
            $this->reset(['selectedApplicant', 'selectedEvaluation']);
            return;
        }

        // Pre-fill selects from the job application's position
        $position = $this->selectedEvaluation->jobApplication->position;
        $this->confirmPositionId   = $position->id            ?? null;
        $this->confirmCollegeId    = $position->college_id    ?? null;
        $this->confirmDepartmentId = $position->department_id ?? null;

        $this->admin_message = '';
        $this->attachments   = [];
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->reset([
            'selectedApplicant', 'selectedEvaluation', 'admin_message',
            'confirmPositionId', 'confirmCollegeId', 'confirmDepartmentId',
            'attachments',
        ]);
    }

    public function removeAttachment($index)
    {
        array_splice($this->attachments, $index, 1);
    }

    public function removeArchiveAttachment($index)
    {
        array_splice($this->archiveAttachments, $index, 1);
    }

    public function confirmAssignment()
    {
        if (!$this->selectedApplicant || !$this->selectedEvaluation) {
            $this->showAlert('error', 'Invalid selection.');
            return;
        }

        $hasPanelComplete = $this->selectedEvaluation->panelAssignments()->where('status', 'complete')->exists();
        $hasNbcComplete   = $this->selectedEvaluation->nbcAssignments()->where('status', 'complete')->exists();

        if (!$hasPanelComplete && !$hasNbcComplete) {
            $this->showAlert('error', 'Cannot assign position. Either Panel Assignment or NBC Assignment must be marked as complete.');
            $this->closeConfirmModal();
            return;
        }

        if (!empty($this->attachments)) {
            $this->validate([
                'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls,ppt,pptx,txt,zip',
            ]);
        }

        DB::beginTransaction();

        try {
            $newPosition   = $this->selectedEvaluation->jobApplication->position->name;
            $oldPosition   = $this->selectedApplicant->position ?? 'None';
            $positionModel = $this->selectedEvaluation->jobApplication->position;

            $this->selectedApplicant->update([
                'position' => $newPosition,
                'hired'    => true,
            ]);

            $this->selectedEvaluation->jobApplication->update([
                'status'  => 'hired',
                'archive' => true,
            ]);

            $storedFiles = $this->storeUploadedFiles($this->attachments, 'assign-position-attachments');

            $this->sendPromotionEmail($this->selectedApplicant, $oldPosition, $positionModel, $this->admin_message, $storedFiles);

            DB::commit();

            $collegeName    = $positionModel->college->name    ?? 'Various Colleges';
            $departmentName = $positionModel->department->name ?? 'Various Departments';

            AccountActivityService::log(
                Auth::user(),
                "Assigned/promoted \"{$this->selectedApplicant->user->name}\" to position \"{$newPosition}\" "
                    . "(previously: \"{$oldPosition}\") — College: {$collegeName}, Department: {$departmentName}."
            );

            $this->showAlert('success', "Successfully assigned {$this->selectedApplicant->user->name} to position: {$newPosition}");
            $this->closeConfirmModal();
            $this->resetPage();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in confirmAssignment: ' . $e->getMessage());
            $this->showAlert('error', 'Something went wrong: ' . $e->getMessage());
            $this->closeConfirmModal();
        }
    }

    // ─── Archive modal ────────────────────────────────────────────────────────
    public function openArchiveModal($jobApplicationId)
    {
        $jobApplication = \App\Models\JobApplication::with(['applicant.user', 'position'])
            ->findOrFail($jobApplicationId);

        if ($jobApplication->status === 'hired') {
            $this->showAlert('error', 'Cannot archive this application. It was used to hire/promote this applicant and must be kept for records.');
            return;
        }

        $this->selectedJobApplication = $jobApplication;
        $this->archive_message        = '';
        $this->archiveAttachments     = [];
        $this->showArchiveModal       = true;
    }

    public function closeArchiveModal()
    {
        $this->showArchiveModal = false;
        $this->reset([
            'selectedJobApplication', 'archive_message', 'archiveAttachments',
        ]);
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

        if (!empty($this->archiveAttachments)) {
            $this->validate([
                'archiveAttachments.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls,ppt,pptx,txt,zip',
            ]);
        }

        try {
            $this->selectedJobApplication->update(['archive' => true]);

            $applicantName  = $this->selectedJobApplication->applicant->user->name;
            $positionName   = $this->selectedJobApplication->position->name;
            $collegeName    = $this->selectedJobApplication->position->college->name    ?? 'Various Colleges';
            $departmentName = $this->selectedJobApplication->position->department->name ?? 'Various Departments';

            AccountActivityService::log(
                Auth::user(),
                "Archived job application for \"{$applicantName}\" — Position: \"{$positionName}\", "
                    . "College: {$collegeName}, Department: {$departmentName}."
            );

            // Only send email if there's a message or attachments
            if (!empty(strip_tags($this->archive_message ?? '')) || !empty($this->archiveAttachments)) {
                $storedFiles = $this->storeUploadedFiles($this->archiveAttachments, 'archive-attachments');
                $this->sendArchiveEmail($this->selectedJobApplication, $this->archive_message, $storedFiles);
            }

            $this->showAlert('success', "Successfully archived job application for {$applicantName}");
            $this->closeArchiveModal();
            $this->resetPage();
        } catch (Exception $e) {
            Log::error('Error in confirmArchive: ' . $e->getMessage());
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
            $jobApplication->update(['archive' => false]);

            $applicantName  = $jobApplication->applicant->user->name;
            $positionName   = $jobApplication->position->name;
            $collegeName    = $jobApplication->position->college->name    ?? 'Various Colleges';
            $departmentName = $jobApplication->position->department->name ?? 'Various Departments';

            AccountActivityService::log(
                Auth::user(),
                "Unarchived job application for \"{$applicantName}\" — Position: \"{$positionName}\", "
                    . "College: {$collegeName}, Department: {$departmentName}."
            );

            $this->showAlert('success', "Successfully unarchived job application for {$applicantName}");
            $this->resetPage();
        } catch (Exception $e) {
            Log::error('Error in unarchive: ' . $e->getMessage());
            $this->showAlert('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // ─── File helper ──────────────────────────────────────────────────────────
    protected function storeUploadedFiles(array $files, string $folder): array
    {
        $stored = [];
        foreach ($files as $file) {
            try {
                $originalName = $file->getClientOriginalName();
                $path         = $file->store($folder, 'local');
                $stored[]     = [
                    'path' => $path,
                    'name' => $originalName,
                    'size' => $file->getSize(),
                ];
            } catch (Exception $e) {
                Log::error("Failed to store file: " . $e->getMessage());
            }
        }
        return $stored;
    }

    // ─── Email helpers ────────────────────────────────────────────────────────
    protected function sendPromotionEmail($applicant, $oldPosition, $position, $adminMessage, array $storedFiles = [])
    {
        try {
            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found");
                return;
            }

            $newPositionName = $position->name;

            $adminMessageBlock = '';
            if (!empty(strip_tags($adminMessage ?? ''))) {
                $adminMessageBlock = "<div style='margin: 16px 0;'>{$adminMessage}</div>";
            }

            $placementRows = '';
            if ($position->college) {
                $placementRows .= "<tr>
                    <td style='padding:6px 12px;color:#4b5563;width:140px;'><strong>College:</strong></td>
                    <td style='padding:6px 12px;'>{$position->college->name}</td>
                </tr>";
            }
            if ($position->department) {
                $placementRows .= "<tr>
                    <td style='padding:6px 12px;color:#4b5563;'><strong>Department:</strong></td>
                    <td style='padding:6px 12px;'>{$position->department->name}</td>
                </tr>";
            }

            $placementBlock = '';
            if ($placementRows) {
                $placementBlock = "
                <div style='background:#f0fdf4;padding:20px;border-radius:8px;margin:20px 0;border-left:4px solid #16a34a;'>
                    <p style='margin:0 0 12px;font-weight:700;color:#15803d;font-size:14px;text-transform:uppercase;letter-spacing:.5px;'>Placement Details</p>
                    <table style='width:100%;border-collapse:collapse;'>
                        <tr>
                            <td style='padding:6px 12px;color:#4b5563;width:140px;'><strong>Position:</strong></td>
                            <td style='padding:6px 12px;font-weight:700;color:#15803d;'>{$newPositionName}</td>
                        </tr>
                        {$placementRows}
                    </table>
                </div>";
            }

            $attachmentBlock = $this->buildAttachmentBlock($storedFiles);

            $messageContent = $this->buildEmailHtml(
                "{$applicant->first_name} {$applicant->last_name}",
                $adminMessageBlock,
                $placementBlock,
                $attachmentBlock,
                'hired'
            );

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject'      => "Hired/Promoted to {$newPositionName}",
                'message'      => $messageContent,
                'attachments'  => !empty($storedFiles) ? $storedFiles : null,
                'is_read'      => false,
                'email_sent'   => false,
            ]);

            $mailable = new NotificationMail($notification);
            foreach ($storedFiles as $file) {
                $absolutePath = Storage::disk('local')->path($file['path']);
                if (Storage::disk('local')->exists($file['path'])) {
                    $mailable->attach($absolutePath, ['as' => $file['name']]);
                }
            }

            Mail::to($applicant->user->email)->queue($mailable);
            $notification->update(['email_sent' => true, 'email_sent_at' => now()]);

            Log::info("Promotion email queued to {$applicant->user->email} ✅");
        } catch (Exception $e) {
            Log::error("Failed to send promotion email: " . $e->getMessage());
        }
    }

    protected function sendArchiveEmail($jobApplication, $adminMessage, array $storedFiles = [])
    {
        try {
            $applicant = $jobApplication->applicant;
            $position  = $jobApplication->position;

            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found for archive notification");
                return;
            }

            $adminMessageBlock = '';
            if (!empty(strip_tags($adminMessage ?? ''))) {
                $adminMessageBlock = "<div style='margin:16px 0;'>{$adminMessage}</div>";
            }

            $attachmentBlock = $this->buildAttachmentBlock($storedFiles);

            $messageContent = $this->buildEmailHtml(
                "{$applicant->first_name} {$applicant->last_name}",
                $adminMessageBlock,
                '',
                $attachmentBlock,
                'archive'
            );

            $notification = Notification::create([
                'applicant_id' => $applicant->id,
                'subject'      => "Job Application Update - {$position->name}",
                'message'      => $messageContent,
                'attachments'  => !empty($storedFiles) ? $storedFiles : null,
                'is_read'      => false,
                'email_sent'   => false,
            ]);

            $mailable = new NotificationMail($notification);
            foreach ($storedFiles as $file) {
                $absolutePath = Storage::disk('local')->path($file['path']);
                if (Storage::disk('local')->exists($file['path'])) {
                    $mailable->attach($absolutePath, ['as' => $file['name']]);
                }
            }

            Mail::to($applicant->user->email)->queue($mailable);
            $notification->update(['email_sent' => true, 'email_sent_at' => now()]);

            Log::info("Archive email queued to {$applicant->user->email} ✅");
        } catch (Exception $e) {
            Log::error("Failed to send archive email: " . $e->getMessage());
        }
    }

    protected function buildAttachmentBlock(array $storedFiles): string
    {
        if (empty($storedFiles)) {
            return '';
        }

        $items = '';
        foreach ($storedFiles as $file) {
            $sizeFormatted = $this->formatBytes($file['size']);
            $items .= "
            <tr>
                <td style='padding:8px 12px;border-bottom:1px solid #e5e7eb;'>
                    <span style='display:inline-flex;align-items:center;gap:8px;'>
                        <span style='width:32px;height:32px;background:#1E7F3E;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:700;flex-shrink:0;'>
                            " . strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION)) . "
                        </span>
                        <span>
                            <span style='display:block;font-size:14px;color:#111827;font-weight:600;'>{$file['name']}</span>
                            <span style='display:block;font-size:12px;color:#6b7280;'>{$sizeFormatted}</span>
                        </span>
                    </span>
                </td>
            </tr>";
        }

        return "
        <div style='margin:20px 0;'>
            <p style='margin:0 0 10px;font-weight:700;color:#1E7F3E;font-size:14px;text-transform:uppercase;letter-spacing:.5px;'>
                📎 Attachments (" . count($storedFiles) . ")
            </p>
            <table style='width:100%;border-collapse:collapse;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;'>
                {$items}
            </table>
            <p style='margin:8px 0 0;font-size:12px;color:#9ca3af;'>Files are attached to this email.</p>
        </div>";
    }

    protected function buildEmailHtml(string $recipientName, string $adminMessageBlock, string $placementBlock, string $attachmentBlock, string $type): string
    {
        $accentColor = $type === 'hired' ? '#1E7F3E' : '#ca8a04';
        $headerBg    = '#1E7F3E';
        $badgeText   = $type === 'hired' ? '🎉 Congratulations!' : '📋 Application Update';

        return "
<div style='font-family:Georgia,\"Times New Roman\",serif;max-width:680px;margin:0 auto;'>
    <div style='background:{$headerBg};padding:0;border-radius:12px 12px 0 0;overflow:hidden;'>
        <div style='background:rgba(0,0,0,.15);height:6px;'></div>
        <div style='padding:32px 40px 28px;text-align:center;'>
            <div style='display:inline-flex;align-items:center;gap:12px;margin-bottom:16px;'>
                <div style='width:52px;height:52px;background:white;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;'>
                    <span style='font-size:24px;'>🌿</span>
                </div>
                <div style='text-align:left;'>
                    <div style='color:white;font-size:18px;font-weight:700;letter-spacing:.5px;'>CLSU FHES</div>
                    <div style='color:rgba(255,255,255,.75);font-size:12px;letter-spacing:1px;text-transform:uppercase;'>Faculty Hiring Evaluation System</div>
                </div>
            </div>
            <div style='color:rgba(255,255,255,.9);font-size:22px;font-weight:700;margin-top:4px;'>{$badgeText}</div>
        </div>
        <div style='background:rgba(0,0,0,.08);height:3px;'></div>
    </div>
    <div style='background:#ffffff;padding:36px 40px;border:1px solid #e5e7eb;border-top:none;'>
        <p style='margin:0 0 20px;font-size:16px;color:#374151;'>
            Dear <strong>{$recipientName}</strong>,
        </p>
        <p style='margin:0 0 20px;font-size:15px;color:#6b7280;'>
            You have received a new notification from the CLSU Faculty Hiring Evaluation System.
        </p>
        <div style='background:#f8fffe;border:1px solid #bbf7d0;border-radius:8px;padding:20px 24px;margin:20px 0;'>
            {$adminMessageBlock}
        </div>
        {$placementBlock}
        {$attachmentBlock}
        <div style='text-align:center;margin:28px 0;'>
            <a href='{{ url(\"/applicant/notifications\") }}'
               style='display:inline-block;padding:14px 32px;background:{$accentColor};color:white;text-decoration:none;border-radius:8px;font-size:15px;font-weight:700;letter-spacing:.3px;'>
                View All Notifications →
            </a>
        </div>
        <p style='margin:24px 0 0;font-size:14px;color:#9ca3af;'>
            If you have any questions, please don't hesitate to contact the HR Department.
        </p>
    </div>
    <div style='background:#f3f4f6;padding:20px 40px;text-align:center;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 12px 12px;'>
        <p style='margin:0 0 6px;font-size:13px;color:#6b7280;'>
            <strong style='color:{$headerBg};'>CLSU HR Department</strong> — Central Luzon State University
        </p>
        <p style='margin:0 0 6px;font-size:12px;color:#9ca3af;'>This is an automated message. Please do not reply to this email.</p>
        <p style='margin:0;font-size:12px;color:#9ca3af;'>© " . date('Y') . " Central Luzon State University. All rights reserved.</p>
    </div>
</div>";
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    // ─── Alert helpers ────────────────────────────────────────────────────────
    protected function showAlert($type, $message)
    {
        $this->alertType      = $type;
        $this->alertMessage   = $message;
        $this->showAlertModal = true;
    }

    public function closeAlertModal()
    {
        $this->showAlertModal = false;
        $this->reset(['alertType', 'alertMessage']);
    }

    // ─── Render ───────────────────────────────────────────────────────────────
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
                            $query->whereHas('panelAssignments', fn($p) => $p->where('status', 'complete'))
                                  ->orWhereHas('nbcAssignments', fn($n) => $n->where('status', 'complete'));
                        });
                    });
            });

        if (!empty($this->search)) {
            $applicantsQuery->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
        }

        if (!empty($this->positionFilter)) {
            $applicantsQuery->whereHas('jobApplications', function ($q) {
                $q->where('archive', $this->showArchived ? true : false)
                    ->where('status', '!=', 'hired')
                    ->whereHas('position', fn($p) => $p->where('name', $this->positionFilter));
            });
        }

        return view('livewire.admin.assign-position', [
            'applicants'         => $applicantsQuery->latest()->paginate($this->perPage),
            'availablePositions' => $this->availablePositions,
            'colleges'           => $this->colleges,
        ]);
    }
}