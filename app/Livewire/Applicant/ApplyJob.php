<?php

namespace App\Livewire\Applicant;

use App\Models\Position;
use Livewire\Component;

class ApplyJob extends Component
{
    public $positions;

    public function mount()
    {
        $this->positions = Position::whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->where('status', 'Vacant') // only vacant positions
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.applicant.apply-job')
            ->layout('layouts.app');
    }
}
