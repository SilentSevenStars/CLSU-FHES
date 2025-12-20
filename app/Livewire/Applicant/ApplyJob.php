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

        // Only show positions that are currently active (between start_date and end_date)
        $query = Position::where('status', 'vacant')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('department', 'like', '%' . $this->search . '%')
                  ->orWhere('college', 'like', '%' . $this->search . '%')
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
            $this->applied = JobApplication::where('applicant_id', $user->applicant->id)
                ->pluck('position_id')
                ->toArray();
        }
    }

    public function viewDetails($positionId)
    {
        $this->selectedPosition = Position::find($positionId);
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

    // Check if user can edit application for this position
    public function canEditApplication($positionId)
    {
        $user = Auth::user();
        if (!$user || !$user->applicant) {
            return false;
        }

        $application = JobApplication::where('applicant_id', $user->applicant->id)
            ->where('position_id', $positionId)
            ->first();

        if (!$application) {
            return false;
        }

        $position = Position::find($positionId);
        $today = Carbon::today();

        // Can edit if today is between start_date and end_date
        return $position && 
               $today->between(
                   Carbon::parse($position->start_date), 
                   Carbon::parse($position->end_date)
               );
    }

    // Get the application ID for editing
    public function getApplicationId($positionId)
    {
        $user = Auth::user();
        if (!$user || !$user->applicant) {
            return null;
        }

        $application = JobApplication::where('applicant_id', $user->applicant->id)
            ->where('position_id', $positionId)
            ->first();

        return $application ? $application->id : null;
    }

    public function render()
    {
        $this->loadAppliedPositions();

        return view('livewire.applicant.apply-job');
    }
}