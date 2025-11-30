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

    protected $listeners = ['job-application-submitted' => 'refreshAppliedPositions'];

    public function mount()
    {
        $this->loadPositions();
    }

    public function loadPositions()
    {
        $today = Carbon::today();

        $this->positions = Position::where('status', 'vacant')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->get();

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

    public function refreshAppliedPositions()
    {
        $this->loadAppliedPositions();
    }

    public function render()
    {
        // Reload applied positions on each render to ensure they're up to date
        $this->loadAppliedPositions();

        return view('livewire.applicant.apply-job')
            ->layout('layouts.app');
    }
}
