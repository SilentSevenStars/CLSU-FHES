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

    public $admin_message = '';

    public $confirmPositionId   = null;
    public $confirmCollegeId    = null;
    public $confirmDepartmentId = null;

    public $attachments = [];

    public $showArchiveModal = false;
    public $selectedJobApplication = null;
    public $showArchived = false;

    public $archive_message = '';

    public $archiveAttachments = [];

    public $hiringRequirementsError = '';

    protected $queryString = [
        'search'         => ['except' => ''],
        'positionFilter' => ['except' => ''],
        'perPage'        => ['except' => 10],
        'showArchived'   => ['except' => false],
    ];

    const SPECIAL_COLLEGES = [
        'College of Engineering',
        'College of Business Administration and Accountancy',
        'College of Veterinary Science and Medicine',
    ];

    const POSITION_RANKS = [
        'Instructor I'          => 1,
        'Instructor II'         => 2,
        'Instructor III'        => 3,
        'Assistant Professor I' => 4,
        'Assistant Professor II'=> 5,
        'Assistant Professor III'=> 6,
        'Assistant Professor IV'=> 7,
        'Associate Professor I' => 8,
        'Associate Professor II'=> 9,
        'Associate Professor III'=> 10,
        'Associate Professor IV'=> 11,
        'Associate Professor V' => 12,
        'Professor I'           => 13,
        'Professor II'          => 14,
        'Professor III'         => 15,
        'Professor IV'          => 16,
        'Professor V'           => 17,
        'Professor VI'          => 18,
    ];

    protected function rules(): array
    {
        return [
            'attachments.*'        => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls,ppt,pptx,txt,zip',
            'archiveAttachments.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls,ppt,pptx,txt,zip',
        ];
    }

    public function updatingSearch()         { $this->resetPage(); }
    public function updatingPositionFilter() { $this->resetPage(); }
    public function updatingPerPage()        { $this->resetPage(); }

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

    protected function checkHiringRequirements(Evaluation $evaluation, string $positionName, ?int $collegeId): ?string
    {
        $rank = self::POSITION_RANKS[$positionName] ?? null;

        $collegeName = $collegeId ? (College::find($collegeId)?->name ?? '') : '';
        $isSpecialCollege = in_array($collegeName, self::SPECIAL_COLLEGES);

        $panelAssignments = $evaluation->panelAssignments()->with(['interview', 'experience', 'performance'])->get();

        $hasPanel = fn(string $relation) => $panelAssignments->contains(
            fn($pa) => !is_null($pa->{$relation . '_id'}) && !is_null($pa->{$relation})
        );

        $hasPanelInterview   = $hasPanel('interview');
        $hasPanelExperience  = $hasPanel('experience');
        $hasPanelPerformance = $hasPanel('performance');

        $hasNbc = $evaluation->nbcAssignments()
            ->where('status', 'complete')
            ->whereNotNull('educational_qualification_id')
            ->whereNotNull('experience_service_id')
            ->whereNotNull('professional_development_id')
            ->exists();

        $missing = [];

        if ($rank === null) {
            $hasPanelComplete = $evaluation->panelAssignments()->where('status', 'complete')->exists();
            $hasNbcComplete   = $evaluation->nbcAssignments()->where('status', 'complete')->exists();
            if (!$hasPanelComplete && !$hasNbcComplete) {
                return 'Cannot assign position. Either Panel Assignment or NBC Assignment must be marked as complete.';
            }
            return null;
        }

        if ($rank <= 2) {
            if (!$hasPanelInterview)   $missing[] = 'Panel Interview';
            if (!$hasPanelExperience)  $missing[] = 'Panel Experience';
            if (!$hasPanelPerformance) $missing[] = 'Panel Performance';
        }

        elseif ($rank >= 3 && $rank <= 4) {
            if ($isSpecialCollege) {
                // Special colleges: Panel Interview + Experience + Performance only
                if (!$hasPanelInterview)   $missing[] = 'Panel Interview';
                if (!$hasPanelExperience)  $missing[] = 'Panel Experience';
                if (!$hasPanelPerformance) $missing[] = 'Panel Performance';
            } else {
                // Other colleges: NBC + Panel Interview + Performance
                if (!$hasNbc)              $missing[] = 'NBC Evaluation (complete with all sub-records)';
                if (!$hasPanelInterview)   $missing[] = 'Panel Interview';
                if (!$hasPanelPerformance) $missing[] = 'Panel Performance';
            }
        }

        elseif ($rank >= 5) {
            if ($isSpecialCollege) {
                // Special colleges: Panel Interview + Performance + NBC
                if (!$hasPanelInterview)   $missing[] = 'Panel Interview';
                if (!$hasPanelPerformance) $missing[] = 'Panel Performance';
                if (!$hasNbc)              $missing[] = 'NBC Evaluation (complete with all sub-records)';
            } else {
                if (!$hasNbc)              $missing[] = 'NBC Evaluation (complete with all sub-records)';
                if (!$hasPanelInterview)   $missing[] = 'Panel Interview';
                if (!$hasPanelPerformance) $missing[] = 'Panel Performance';
            }
        }

        if (empty($missing)) {
            return null;
        }

        $list = implode(', ', $missing);
        return "Cannot assign position \"{$positionName}\". The following evaluations are required but missing or incomplete: {$list}.";
    }

    public function openConfirmModal($applicantId, $evaluationId)
    {
        $this->selectedApplicant  = Applicant::with(['user', 'jobApplications.position'])->findOrFail($applicantId);
        $this->selectedEvaluation = Evaluation::with([
            'jobApplication.position.college',
            'jobApplication.position.department',
            'jobApplication.applicant',
            'panelAssignments.interview',
            'panelAssignments.experience',
            'panelAssignments.performance',
            'nbcAssignments',
        ])->findOrFail($evaluationId);

        // Pre-fill selects from the job application's position.
        $position  = $this->selectedEvaluation->jobApplication->position;
        $applicant = $this->selectedEvaluation->jobApplication->applicant;

        $this->confirmPositionId   = $position->id ?? null;
        $this->confirmCollegeId    = $position->college_id    ?? $applicant->college_id    ?? null;
        $this->confirmDepartmentId = $position->department_id ?? $applicant->department_id ?? null;

        // Run requirements check
        $positionName = $position->name ?? '';
        $error = $this->checkHiringRequirements(
            $this->selectedEvaluation,
            $positionName,
            $this->confirmCollegeId
        );

        if ($error) {
            $this->hiringRequirementsError = $error;
            $this->showAlert('error', $error);
            $this->reset(['selectedApplicant', 'selectedEvaluation', 'confirmPositionId', 'confirmCollegeId', 'confirmDepartmentId']);
            return;
        }

        $this->hiringRequirementsError = '';
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
            'attachments', 'hiringRequirementsError',
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

        $position     = $this->selectedEvaluation->jobApplication->position;
        $positionName = $position->name ?? '';
        $error = $this->checkHiringRequirements(
            $this->selectedEvaluation,
            $positionName,
            $this->confirmCollegeId
        );

        if ($error) {
            $this->showAlert('error', $error);
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
            $newPosition   = $position->name;
            $oldPosition   = $this->selectedApplicant->position ?? 'None';
            $positionModel = $position;

            $effectiveCollegeId    = $this->confirmCollegeId    ?? null;
            $effectiveDepartmentId = $this->confirmDepartmentId ?? null;

            // Update the applicant: position, hired flag, AND college/department
            $this->selectedApplicant->update([
                'position'      => $newPosition,
                'hired'         => true,
                'college_id'    => $effectiveCollegeId,
                'department_id' => $effectiveDepartmentId,
            ]);

            $this->selectedEvaluation->jobApplication->update([
                'status'  => 'hired',
                'archive' => true,
            ]);

            $storedFiles = $this->storeUploadedFiles($this->attachments, 'assign-position-attachments');

            $this->sendPromotionEmail(
                $this->selectedApplicant,
                $oldPosition,
                $positionModel,
                $effectiveCollegeId,
                $effectiveDepartmentId,
                $this->admin_message,
                $storedFiles
            );

            DB::commit();

            $collegeName    = College::find($effectiveCollegeId)?->name    ?? 'Various Colleges';
            $departmentName = Department::find($effectiveDepartmentId)?->name ?? 'Various Departments';

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

    protected function buildNotificationBody(
        string $recipientName,
        string $adminMessage,
        string $positionName,
        ?string $collegeName,
        ?string $departmentName,
        string $type
    ): string {
        $accentColor = $type === 'hired' ? '#1E7F3E' : '#ca8a04';

        $adminSection = !empty(strip_tags($adminMessage))
            ? "<div style='margin:16px 0;font-size:14px;color:#374151;line-height:1.6;'>{$adminMessage}</div>"
            : '';

        $placementBlock = '';
        if ($type === 'hired') {
            $rows = "<tr>
                <td style='padding:6px 12px;color:#4b5563;width:140px;font-size:14px;'><strong>Position:</strong></td>
                <td style='padding:6px 12px;font-weight:700;color:#15803d;font-size:14px;'>{$positionName}</td>
            </tr>";

            if ($collegeName) {
                $rows .= "<tr>
                    <td style='padding:6px 12px;color:#4b5563;font-size:14px;'><strong>College:</strong></td>
                    <td style='padding:6px 12px;font-size:14px;'>{$collegeName}</td>
                </tr>";
            }

            if ($departmentName) {
                $rows .= "<tr>
                    <td style='padding:6px 12px;color:#4b5563;font-size:14px;'><strong>Department:</strong></td>
                    <td style='padding:6px 12px;font-size:14px;'>{$departmentName}</td>
                </tr>";
            }

            $placementBlock = "
            <div style='background:#f0fdf4;padding:20px;border-radius:8px;margin:20px 0;border-left:4px solid #16a34a;'>
                <p style='margin:0 0 12px;font-weight:700;color:#15803d;font-size:14px;text-transform:uppercase;letter-spacing:.5px;'>Placement Details</p>
                <table style='width:100%;border-collapse:collapse;'>{$rows}</table>
            </div>";
        }

        return "
        <p style='margin:0 0 16px;font-size:16px;color:#374151;'>
            Dear <strong>{$recipientName}</strong>,
        </p>
        <p style='margin:0 0 20px;font-size:15px;color:#6b7280;'>
            You have received a new notification from the CLSU Faculty Hiring Evaluation System.
        </p>
        {$adminSection}
        {$placementBlock}
        <p style='margin:24px 0 0;font-size:14px;color:#9ca3af;'>
            If you have any questions, please don't hesitate to contact the HR Department.
        </p>";
    }

    protected function sendPromotionEmail(
        $applicant,
        $oldPosition,
        $positionModel,
        ?int $effectiveCollegeId,
        ?int $effectiveDepartmentId,
        $adminMessage,
        array $storedFiles = []
    ) {
        try {
            if (!$applicant || !$applicant->user) {
                Log::error("Applicant or user not found");
                return;
            }

            $newPositionName = $positionModel->name;
            $collegeName     = College::find($effectiveCollegeId)?->name     ?? null;
            $departmentName  = Department::find($effectiveDepartmentId)?->name ?? null;

            $messageContent = $this->buildNotificationBody(
                "{$applicant->first_name} {$applicant->last_name}",
                $adminMessage ?? '',
                $newPositionName,
                $collegeName,
                $departmentName,
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

            Mail::to($applicant->user->email)->send($mailable);
            $notification->update(['email_sent' => true, 'email_sent_at' => now()]);

            Log::info("Promotion email sent to {$applicant->user->email} ✅");
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

            $messageContent = $this->buildNotificationBody(
                "{$applicant->first_name} {$applicant->last_name}",
                $adminMessage ?? '',
                $position->name,
                null,
                null,
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

            Mail::to($applicant->user->email)->send($mailable);
            $notification->update(['email_sent' => true, 'email_sent_at' => now()]);

            Log::info("Archive email sent to {$applicant->user->email} ✅");
        } catch (Exception $e) {
            Log::error("Failed to send archive email: " . $e->getMessage());
        }
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

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