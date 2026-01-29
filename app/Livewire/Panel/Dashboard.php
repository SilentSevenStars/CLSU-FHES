<?php

namespace App\Livewire\Panel;

use App\Models\JobApplication;
use App\Models\Panel;
use App\Models\PanelAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        // Load panel with relationships
        $panel = Panel::with(['college', 'department'])
            ->where('user_id', $user->id)
            ->first();

        if (!$panel) {
            return view('livewire.panel.dashboard', [
                'applications' => collect(),
                'panel' => null,
                'assignments' => collect(),
            ]);
        }

        // Allowed position names (whitelist)
        $allowedPositions = [
            'Instructor I',
            'Instructor II',
            'Instructor III',
            'Assistant Professor I',
            'Assistant Professor II',
            'Assistant Professor III',
            'Assistant Professor IV',
            'Associate Professor I',
            'Associate Professor II',
            'Associate Professor III',
            'Associate Professor IV',
            'Associate Professor V',
            'Professor I',
            'Professor II'
        ];

        // Base query with eager loading
        $query = JobApplication::query()
            ->whereHas('evaluation', function ($q) {
                $q->whereDate('interview_date', today());
            })
            ->whereHas('position', function ($q) use ($allowedPositions) {
                $q->whereIn('name', $allowedPositions);
            });

        // Filter by panel position using foreign keys
        $panelPos = strtolower($panel->panel_position);
        
        if (in_array($panelPos, ['head', 'senior', 'señior'])) {
            // Filter by both college_id AND department_id (foreign keys)
            $query->whereHas('position', function ($q) use ($panel) {
                $q->where('college_id', $panel->college_id)
                  ->where('department_id', $panel->department_id);
            });
        }

        if ($panelPos === 'dean') {
            // Filter by college_id only (foreign key)
            $query->whereHas('position', function ($q) use ($panel) {
                $q->where('college_id', $panel->college_id);
            });
        }

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('applicant', function ($sub) {
                    $sub->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%");
                })
                ->orWhereHas('applicant.user', function ($sub) {
                    $sub->where('email', 'like', "%{$this->search}%");
                });
            });
        }

        // Get all matching applications with eager loading
        $applications = $query->with([
            'applicant.user', 
            'position.college',      // Eager load college relationship
            'position.department',   // Eager load department relationship
            'evaluation'
        ])->get();

        // Get assignments for this panel
        $assignments = PanelAssignment::where('panel_id', $panel->id)
            ->get()
            ->keyBy('evaluation_id');

        // Sort: Pending first, then Completed
        $sortedApplications = $applications->sort(function ($a, $b) use ($assignments) {
            $evalA = $a->evaluation;
            $evalB = $b->evaluation;
            
            $assignmentA = $assignments[$evalA->id] ?? null;
            $assignmentB = $assignments[$evalB->id] ?? null;
            
            $isCompleteA = $assignmentA && $assignmentA->status === 'complete';
            $isCompleteB = $assignmentB && $assignmentB->status === 'complete';
            
            // Pending (false) comes before Complete (true)
            if ($isCompleteA === $isCompleteB) {
                return 0;
            }
            return $isCompleteA ? 1 : -1;
        });

        // Calculate totals for summary cards
        $totalCount = $applications->count();
        $completedCount = $applications->filter(function($app) use ($assignments) {
            $evaluation = $app->evaluation;
            $assignment = $assignments[$evaluation->id] ?? null;
            return $assignment && $assignment->status === 'complete';
        })->count();
        $pendingCount = $totalCount - $completedCount;

        // Paginate the sorted collection
        $perPage = 10;
        $currentPage = $this->getPage();
        $paginatedApplications = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedApplications->forPage($currentPage, $perPage),
            $sortedApplications->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        return view('livewire.panel.dashboard', [
            'applications' => $paginatedApplications,
            'panel' => $panel,
            'assignments' => $assignments,
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'pendingCount' => $pendingCount,
        ]);
    }
}