<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantStatusMail;

class AssignPosition extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    /* Filters */
    public $search = '';
    public $positionFilter = '';
    public $perPage = 10;

    /* Modal */
    public $showStatusModal = false;

    /* Form */
    public $jobApplicationId;

    /* Mail content */
    public $statusMessage = '';
    public $statusType = 'success';

    public function render()
    {
        $applications = JobApplication::with(['applicant', 'position', 'evaluation'])
            ->whereHas('applicant.user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->positionFilter, fn($q) => $q->where('position_id', $this->positionFilter))
            ->paginate($this->perPage);

        return view('livewire.admin.assign-position', [
            'positions' => \App\Models\Position::all(),
            'applications' => $applications
        ]);
    }

    /* Open modal to send status */
    public function openStatusModal($applicationId)
    {
        $this->jobApplicationId = $applicationId;
        $this->showStatusModal = true;
    }

    /* Assign position and send Gmail */
    public function sendStatus()
    {
        $application = JobApplication::with(['applicant', 'position', 'evaluation'])
            ->findOrFail($this->jobApplicationId);

        if (!$application->evaluation || !$application->evaluation->interview_date) {
            $this->statusType = 'error';
            $this->statusMessage = "No interview date set.";
            $this->showStatusModal = true;
            return;
        }

        $interviewDate = \Carbon\Carbon::parse($application->evaluation->interview_date);

        if ($interviewDate->isPast()) {
            $applicant = $application->applicant;
            $applicant->position = $application->position->name;
            $saved = $applicant->save();

            try {
                Mail::to($applicant->user->email)->send(
                    new ApplicantStatusMail(
                        $applicant->user->name,
                        'assigned',
                        $applicant->position
                    )
                );
                $this->statusType = 'success';
                $this->statusMessage = "Position '{$applicant->position}' assigned and Gmail sent successfully.";
            } catch (\Exception $e) {
                $this->statusType = 'error';
                $this->statusMessage = "Position assigned but email failed: " . $e->getMessage();
            }
        } else {
            $this->statusType = 'info';
            $this->statusMessage = "Interview date is not yet past. Cannot assign position.";
        }

        $this->showStatusModal = true;
    }

    public function closeModal()
    {
        $this->showStatusModal = false;
    }
}
