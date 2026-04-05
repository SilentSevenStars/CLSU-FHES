<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Position;
use App\Models\College;
use App\Models\Department;
use App\Models\PanelAssignment;
use App\Models\Performance as PerformanceModel;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;

class Performance extends Component
{
    // ── Filters ────────────────────────────────────────────────────────────────
    public string $searchTerm         = '';
    public string $selectedPosition   = '';
    public string $selectedDate       = '';
    public string $selectedCollege    = '';
    public string $selectedDepartment = '';
    public string $selectedStatus     = '';

    // ── Filter options ─────────────────────────────────────────────────────────
    public array $positions      = [];
    public array $interviewDates = [];
    public array $colleges       = [];
    public array $departments    = [];

    // ── Table data ─────────────────────────────────────────────────────────────
    public array $applicants = [];

    // ── Modal state ────────────────────────────────────────────────────────────
    public bool   $showModal      = false;
    public ?int   $modalEvalId    = null;
    public string $modalApplicant = '';
    public string $modalPosition  = '';
    public string $modalError     = '';
    public string $scoreInput     = '';

    private array $eligiblePositions = [
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

    // ── Lifecycle ──────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->loadFilterOptions();
        $this->loadApplicants();
    }

    // ── Filter helpers ─────────────────────────────────────────────────────────

    public function loadFilterOptions(): void
    {
        $existing = Position::whereIn('name', $this->eligiblePositions)
            ->whereHas('jobApplications', function ($q) {
                $q->whereIn('status', ['approve', 'hired'])
                  ->whereHas('evaluation');
            })
            ->pluck('name')->unique()->toArray();

        $this->positions = collect($this->eligiblePositions)
            ->filter(fn($p) => in_array($p, $existing))
            ->values()->toArray();

        $this->colleges = College::orderBy('name')->pluck('name', 'id')->toArray();

        $deptQuery = Department::orderBy('name');
        if ($this->selectedCollege) {
            $deptQuery->where('college_id', $this->selectedCollege);
        }
        $this->departments = $deptQuery->pluck('name', 'id')->toArray();

        $this->interviewDates = $this->buildEvaluationQuery()
            ->whereNotNull('interview_date')
            ->get()
            ->pluck('interview_date')
            ->unique()->sort()->values()->toArray();
    }

    public function updatedSelectedCollege(): void
    {
        $this->selectedDepartment = '';
        $this->selectedDate       = '';
        $this->loadFilterOptions();
        $this->loadApplicants();
    }

    public function updatedSelectedDepartment(): void
    {
        $this->selectedDate = '';
        $this->loadFilterOptions();
        $this->loadApplicants();
    }

    public function updatedSelectedPosition(): void
    {
        $this->selectedDate = '';
        $this->loadFilterOptions();
        $this->loadApplicants();
    }

    public function updatedSelectedDate(): void   { $this->loadApplicants(); }
    public function updatedSearchTerm(): void     { $this->loadApplicants(); }
    public function updatedSelectedStatus(): void { $this->loadApplicants(); }

    // ── Base query ─────────────────────────────────────────────────────────────

    private function buildEvaluationQuery()
    {
        return Evaluation::with([
            'jobApplication.applicant',
            'jobApplication.position.college',
            'jobApplication.position.department',
            'panelAssignments.performance',
            'panelAssignments.user',
        ])->whereHas('jobApplication', function ($q) {
            $q->whereIn('status', ['approve', 'hired'])
              ->whereHas('position', function ($pq) {
                  $pq->whereIn('name', $this->eligiblePositions);
                  if ($this->selectedPosition)  $pq->where('name', $this->selectedPosition);
                  if ($this->selectedCollege)    $pq->where('college_id', $this->selectedCollege);
                  if ($this->selectedDepartment) $pq->where('department_id', $this->selectedDepartment);
              });
        })->when($this->selectedDate, fn($q) => $q->whereDate('interview_date', $this->selectedDate));
    }

    // ── Applicant list ─────────────────────────────────────────────────────────

    public function loadApplicants(): void
    {
        $evaluations = $this->buildEvaluationQuery()->get();

        if (trim($this->searchTerm) !== '') {
            $search = strtolower(trim($this->searchTerm));
            $evaluations = $evaluations->filter(function ($ev) use ($search) {
                $a    = $ev->jobApplication->applicant;
                $full = strtolower(trim(
                    ($a->first_name  ?? '') . ' ' .
                    ($a->middle_name ?? '') . ' ' .
                    ($a->last_name   ?? '')
                ));
                return str_contains($full, $search);
            });
        }

        $mapped = $evaluations->map(function ($evaluation) {
            $a   = $evaluation->jobApplication->applicant;
            $p   = $evaluation->jobApplication->position;
            $pas = $evaluation->panelAssignments ?? collect();

            $withPerformance = $pas->filter(
                fn($pa) => $pa->performance !== null && $pa->performance->total_score !== null
            );

            $hasAnyPerformance = $withPerformance->isNotEmpty();

            $avgPerformance = $hasAnyPerformance
                ? round($withPerformance->pluck('performance.total_score')->filter()->avg(), 2)
                : null;

            $adminAlreadyScored = $pas->contains(
                fn($pa) => $pa->user_id == Auth::id() && $pa->performance_id !== null
            );

            $overallStatus = $hasAnyPerformance ? 'complete' : 'not yet';

            return [
                'evaluation_id'        => $evaluation->id,
                'name'                 => trim(
                    ($a->first_name  ?? '') . ' ' .
                    ($a->middle_name ?? '') . ' ' .
                    ($a->last_name   ?? '')
                ),
                'last_name'            => $a->last_name ?? '',
                'position'             => $p->name ?? 'N/A',
                'specialization'       => $p->specialization ?? 'N/A',
                'college'              => $p->college->name  ?? 'N/A',
                'department'           => $p->department->name ?? 'N/A',
                'interview_date'       => $evaluation->interview_date,
                'has_performance'      => $hasAnyPerformance,
                'avg_performance'      => $avgPerformance,
                'scored_by'            => $withPerformance->map(fn($pa) => [
                    'user_name'   => $pa->user->name ?? '—',
                    'total_score' => $pa->performance->total_score,
                ])->values()->toArray(),
                'admin_already_scored' => $adminAlreadyScored,
                'status'               => $overallStatus,
            ];
        });

        if ($this->selectedStatus !== '') {
            $mapped = $mapped->filter(
                fn($row) => $row['status'] === $this->selectedStatus
            );
        }

        $this->applicants = $mapped
            ->sortBy(fn($row) => strtolower($row['last_name']))
            ->values()
            ->toArray();
    }

    // ── Modal ──────────────────────────────────────────────────────────────────

    public function openModal(int $evaluationId): void
    {
        $row = collect($this->applicants)->firstWhere('evaluation_id', $evaluationId);

        if (!$row || $row['admin_already_scored']) {
            return;
        }

        $evaluation = Evaluation::with('jobApplication.applicant', 'jobApplication.position')
            ->find($evaluationId);

        if (!$evaluation) return;

        $a = $evaluation->jobApplication->applicant;
        $p = $evaluation->jobApplication->position;

        $this->modalEvalId    = $evaluationId;
        $this->modalApplicant = trim(
            ($a->first_name  ?? '') . ' ' .
            ($a->middle_name ?? '') . ' ' .
            ($a->last_name   ?? '')
        );
        $this->modalPosition  = $p->name ?? 'N/A';
        $this->scoreInput     = '';
        $this->modalError     = '';
        $this->showModal      = true;
    }

    public function closeModal(): void
    {
        $this->showModal   = false;
        $this->modalEvalId = null;
        $this->scoreInput  = '';
        $this->modalError  = '';
    }

    // ── Save ───────────────────────────────────────────────────────────────────

    public function saveScore(): void
    {
        $this->modalError = '';

        if ($this->scoreInput === '' || $this->scoreInput === null) {
            $this->modalError = 'Please enter a performance score.';
            return;
        }

        $score   = (float) $this->scoreInput;
        $adminId = Auth::id();

        if ($score < 0 || $score > 30) {
            $this->modalError = 'Score must be between 0 and 30.';
            return;
        }

        if (!$adminId) {
            $this->modalError = 'You must be logged in to submit a score.';
            return;
        }

        $evaluation = Evaluation::with('panelAssignments.performance')->find($this->modalEvalId);

        if (!$evaluation) {
            $this->modalError = 'Evaluation record not found.';
            return;
        }

        $alreadyScored = ($evaluation->panelAssignments ?? collect())->contains(
            fn($pa) => $pa->user_id == $adminId && $pa->performance_id !== null
        );

        if ($alreadyScored) {
            $this->modalError = 'You have already submitted a performance score for this applicant.';
            return;
        }

        $performance = PerformanceModel::create([
            'total_score' => (int) round($score),
        ]);

        $existingPa = ($evaluation->panelAssignments ?? collect())->first(
            fn($pa) => $pa->user_id == $adminId && $pa->performance_id === null
        );

        if ($existingPa) {
            $existingPa->update([
                'performance_id' => $performance->id,
                'status'         => 'complete',
            ]);
        } else {
            PanelAssignment::create([
                'user_id'        => $adminId,
                'evaluation_id'  => $evaluation->id,
                'performance_id' => $performance->id,
                'status'         => 'complete',
            ]);
        }

        AccountActivityService::log(
            Auth::user(),
            "Admin added performance score ({$score}) for applicant \"{$this->modalApplicant}\" "
            . "applying for \"{$this->modalPosition}\" "
            . "(Evaluation ID: {$evaluation->id})."
        );

        $applicantName = $this->modalApplicant;

        $this->closeModal();
        $this->loadApplicants();

        session()->flash('success', "Performance score of {$score} saved for {$applicantName}.");
    }

    // ── Render ─────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin.performance');
    }
}