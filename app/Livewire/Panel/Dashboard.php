<?php

namespace App\Livewire\Panel;

use App\Models\JobApplication;
use App\Models\Panel;
use App\Models\PanelAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';

    public function render()
    {
        $user = Auth::user();

        $panel = Panel::where('user_id', $user->id)->first();

        if (!$panel) {
            return view('livewire.panel.dashboard', [
                'applications' => collect(),
                'panel' => null,
                'assignments' => collect(),
            ]);
        }

        $query = JobApplication::query()
            ->whereHas('evaluation', function ($q) {
                $q->whereDate('interview_date', today());
            });

        $panelPos = strtolower($panel->panel_position);
        if (in_array($panelPos, ['head', 'senior', 'señior'])) {
            $query->whereHas('position', function ($q) use ($panel) {
                $q->where('college', $panel->college)
                  ->where('department', $panel->department);
            });
        }

        if ($panelPos === 'dean') {
            $query->whereHas('position', function ($q) use ($panel) {
                $q->where('college', $panel->college);
            });
        }

        $query->where(function ($q) {
            $q->whereHas('applicant', function ($sub) {
                $sub->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%");
            })
            ->orWhereHas('applicant.user', function ($sub) {
                $sub->where('email', 'like', "%{$this->search}%");
            });
        });

        $applications = $query->with(['applicant.user', 'position', 'evaluation'])->get();

        $assignments = PanelAssignment::where('panel_id', $panel->id)
            ->get()
            ->keyBy('evaluation_id');

        return view('livewire.panel.dashboard', [
            'applications' => $applications,
            'panel' => $panel,
            'assignments' => $assignments,
        ])->layout('layouts.app');
    }
}
