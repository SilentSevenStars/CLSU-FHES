<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $positionLabels = [];
    public $positionCounts = [];

    public $collegeLabels = [];
    public $collegeCounts = [];

    public function mount()
    {
        $this->loadCharts();
    }

    public function loadCharts()
    {
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $positionData = JobApplication::with('position')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->groupBy(fn($app) => $app->position->name)
            ->map->count();

        $this->positionLabels = $positionData->keys()->values();
        $this->positionCounts = $positionData->values();

        $collegeData = JobApplication::with('position')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->groupBy(fn($app) => $app->position->college)
            ->map->count();

        $this->collegeLabels = $collegeData->keys()->values();
        $this->collegeCounts = $collegeData->values();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.app');
    }
}
