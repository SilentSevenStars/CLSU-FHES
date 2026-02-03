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

    public $currentMonthName = '';
    public $currentYear = '';

    public function mount()
    {
        $this->loadCharts();
    }

    public function loadCharts()
    {
        $now = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        // Set month name and year for display
        $this->currentMonthName = $now->format('F'); // Full month name (e.g., "January")
        $this->currentYear = $now->format('Y'); // Year (e.g., "2026")

        $positionData = JobApplication::with('position')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->groupBy(fn($app) => $app->position->name)
            ->map->count();

        $this->positionLabels = $positionData->keys()->values();
        $this->positionCounts = $positionData->values();

        // Load college relationship properly
        $collegeData = JobApplication::with('position.college')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->groupBy(fn($app) => $app->position->college->name ?? 'Unknown')
            ->map->count();

        $this->collegeLabels = $collegeData->keys()->values();
        $this->collegeCounts = $collegeData->values();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}