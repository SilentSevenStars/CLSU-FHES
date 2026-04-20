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

    private const UNIVERSITY_POSITIONS = [
        'chair_fsb',
        'fai_president',
        'clutches_president',
        'director_hr',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Resolve the effective college_id and department_id for an application.
     * If position has no college/department, fall back to the applicant's values.
     */
    private function resolveCollegeAndDepartment(JobApplication $app): array
    {
        $position  = $app->position;
        $applicant = $app->applicant;

        $posCollegeId    = $position?->college_id;
        $posDepartmentId = $position?->department_id;

        // Fallback: if position has no college AND no department, use applicant's
        if (is_null($posCollegeId) && is_null($posDepartmentId)) {
            return [
                'college_id'    => $applicant?->college_id,
                'department_id' => $applicant?->department_id,
            ];
        }

        return [
            'college_id'    => $posCollegeId,
            'department_id' => $posDepartmentId,
        ];
    }

    public function render()
    {
        $user = Auth::user();

        $panel = Panel::with(['college', 'department'])
            ->where('user_id', $user->id)
            ->first();

        if (!$panel) {
            return view('livewire.panel.dashboard', [
                'applications' => collect(),
                'panel'        => null,
                'assignments'  => collect(),
            ]);
        }

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
            'Professor II',
        ];

        // Base query — fetch all matching applications; filtering by scope is done in PHP
        $query = JobApplication::query()
            ->whereHas('evaluation', function ($q) {
                $q->whereDate('interview_date', today());
            })
            ->whereHas('position', function ($q) use ($allowedPositions) {
                $q->whereIn('name', $allowedPositions);
            });

        // Eager-load everything needed, including applicant's college/department
        $applications = $query->with([
            'applicant.user',
            'applicant',           // includes college_id / department_id
            'position.college',
            'position.department',
            'evaluation',
        ])->get();

        $panelPos          = strtolower($panel->panel_position);
        $isUniversityLevel = in_array($panelPos, self::UNIVERSITY_POSITIONS);

        // PHP-side scope filter using resolved college/department
        if (! $isUniversityLevel) {
            $applications = $applications->filter(function ($app) use ($panel, $panelPos) {
                ['college_id' => $effectiveCollege, 'department_id' => $effectiveDepartment]
                    = $this->resolveCollegeAndDepartment($app);

                if ($panelPos === 'dean') {
                    if (is_null($panel->college_id)) {
                        return true; // Dean with no college → sees all
                    }
                    // Match college, or no effective college (university-wide position)
                    return is_null($effectiveCollege)
                        || $effectiveCollege == $panel->college_id;

                } elseif (in_array($panelPos, ['head', 'senior', 'señior'])) {
                    if (is_null($panel->college_id)) {
                        return true; // No college assigned → sees all
                    }

                    if (is_null($panel->department_id)) {
                        // College-level head: match college or no effective college
                        return is_null($effectiveCollege)
                            || $effectiveCollege == $panel->college_id;
                    }

                    // Department-level head: match college+department, college-only, or university-wide
                    if (is_null($effectiveCollege)) {
                        return true; // University-wide position
                    }

                    if ($effectiveCollege == $panel->college_id) {
                        // Same college: match department or no department set
                        return is_null($effectiveDepartment)
                            || $effectiveDepartment == $panel->department_id;
                    }

                    return false;
                }

                return true; // Unknown panel position → show all
            });
        }

        // PHP-side search
        if ($this->search) {
            $search       = strtolower($this->search);
            $applications = $applications->filter(function ($app) use ($search) {
                $name     = strtolower($app->applicant?->user?->name ?? '');
                $email    = strtolower($app->applicant?->user?->email ?? '');
                $position = strtolower($app->position?->name ?? '');

                return str_contains($name, $search)
                    || str_contains($email, $search)
                    || str_contains($position, $search);
            });
        }

        // Get assignments keyed by evaluation_id
        $assignments = PanelAssignment::where('user_id', $user->id)
            ->get()
            ->keyBy('evaluation_id');

        // Sort: Pending first, then Completed
        $sortedApplications = $applications->sort(function ($a, $b) use ($assignments) {
            $isCompleteA = isset($assignments[$a->evaluation->id])
                && $assignments[$a->evaluation->id]->status === 'complete';
            $isCompleteB = isset($assignments[$b->evaluation->id])
                && $assignments[$b->evaluation->id]->status === 'complete';

            if ($isCompleteA === $isCompleteB) return 0;
            return $isCompleteA ? 1 : -1;
        });

        // Summary counts
        $totalCount     = $applications->count();
        $completedCount = $applications->filter(function ($app) use ($assignments) {
            $assignment = $assignments[$app->evaluation->id] ?? null;
            return $assignment && $assignment->status === 'complete';
        })->count();
        $pendingCount = $totalCount - $completedCount;

        // Paginate
        $perPage     = 10;
        $currentPage = $this->getPage();
        $paginatedApplications = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedApplications->forPage($currentPage, $perPage),
            $sortedApplications->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        return view('livewire.panel.dashboard', [
            'applications'   => $paginatedApplications,
            'panel'          => $panel,
            'assignments'    => $assignments,
            'totalCount'     => $totalCount,
            'completedCount' => $completedCount,
            'pendingCount'   => $pendingCount,
        ]);
    }
}