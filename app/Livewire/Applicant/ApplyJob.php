<?php

namespace App\Livewire\Applicant;

use App\Models\JobApplication;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplyJob extends Component
{
    public $positions;
    public $applied = [];
    public $search = '';
    public $selectedPosition = null;
    public $showModal = false;

    protected $listeners = ['job-application-submitted' => 'refreshAppliedPositions'];

    public function mount()
    {
        $this->loadPositions();
    }

    public function updatedSearch()
    {
        $this->loadPositions();
    }

    public function loadPositions()
    {
        $today = Carbon::today();

        $query = Position::with(['college', 'department'])
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('department', fn($dq) => $dq->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('college', fn($cq) => $cq->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhere('specialization', 'like', '%' . $this->search . '%');
            });
        }

        $this->positions = $query->orderBy('start_date', 'asc')->get();
        $this->loadAppliedPositions();
    }

    public function loadAppliedPositions()
    {
        $user = Auth::user();
        if ($user && $user->applicant) {
            // Exclude archived, hired, and declined applications so applicants
            // can freely re-apply after any of those outcomes.
            // NOTE: the DB enum value is 'decline' (not 'declined').
            $this->applied = JobApplication::where('applicant_id', $user->applicant->id)
                ->where('archive', false)
                ->whereNotIn('status', ['hired', 'decline'])
                ->pluck('position_id')
                ->toArray();
        } else {
            $this->applied = [];
        }
    }

    public function viewDetails($positionId)
    {
        $this->selectedPosition = Position::with(['college', 'department'])->find($positionId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPosition = null;
    }

    public function refreshAppliedPositions()
    {
        $this->loadAppliedPositions();
        $this->loadPositions();
    }

    public function canEditApplication($positionId)
    {
        $user = Auth::user();
        if (!$user || !$user->applicant) {
            return false;
        }

        $application = JobApplication::where('applicant_id', $user->applicant->id)
            ->where('position_id', $positionId)
            ->whereNotIn('status', ['hired', 'decline'])
            ->where('archive', false)
            ->first();

        if (!$application) {
            return false;
        }

        $position = Position::find($positionId);
        $today = Carbon::today();

        return $position &&
            $today->between(
                Carbon::parse($position->start_date),
                Carbon::parse($position->end_date)
            );
    }

    public function getApplicationId($positionId)
    {
        $user = Auth::user();
        if (!$user || !$user->applicant) {
            return null;
        }

        $application = JobApplication::where('applicant_id', $user->applicant->id)
            ->where('position_id', $positionId)
            ->whereNotIn('status', ['hired', 'decline'])
            ->where('archive', false)
            ->first();

        return $application ? $application->id : null;
    }

    public function render()
    {
        $this->loadAppliedPositions();

        // Has active = applicant has at least one non-archived, non-hired, non-declined application
        $hasActiveApplication = !empty($this->applied);

        return view('livewire.applicant.apply-job', [
            'hasActiveApplication' => $hasActiveApplication,
        ]);
    }
}