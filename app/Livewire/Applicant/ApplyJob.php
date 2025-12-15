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
    }

    public function render()
    {
        $this->loadAppliedPositions();

        return view('livewire.applicant.apply-job');
    }
}