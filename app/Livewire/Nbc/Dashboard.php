<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use App\Models\NbcCommittee;
use App\Models\NbcAssignment;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        if (!$nbcCommittee) {
            abort(403, 'Unauthorized. You are not registered as an NBC Committee member.');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function getEvaluationsProperty()
    {
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        if (!$nbcCommittee) {
            return collect()->paginate($this->perPage);
        }

        $query = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])
        // Show all evaluations where today is on or before the interview_date (deadline)
        // interview_date is the DEADLINE — as long as today <= interview_date, it is accessible
        ->whereDate('interview_date', '>=', today());

        // Search filter - name or position (no position restriction — all positions are included)
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('jobApplication.applicant', function ($applicantQuery) {
                    $applicantQuery->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('middle_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('jobApplication.position', function ($positionQuery) {
                    $positionQuery->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Sort: pending first, complete last; then alphabetically by applicant last_name
        $query->orderByRaw('
            CASE
                WHEN EXISTS (
                    SELECT 1 FROM nbc_assignments
                    WHERE nbc_assignments.evaluation_id = evaluations.id
                    AND nbc_assignments.status = "complete"
                    AND EXISTS (
                        SELECT 1 FROM nbc_committees
                        WHERE nbc_committees.id = nbc_assignments.nbc_committee_id
                        AND nbc_committees.user_id = ?
                    )
                ) THEN 1
                ELSE 0
            END
        ', [Auth::id()])
        ->orderBy(
            \App\Models\Applicant::select('last_name')
                ->join('job_applications', 'job_applications.applicant_id', '=', 'applicants.id')
                ->whereColumn('job_applications.id', 'evaluations.job_application_id')
                ->limit(1),
            'asc'
        );

        return $query->paginate($this->perPage);
    }

    public function getPendingTodayCountProperty()
    {
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        if (!$nbcCommittee) {
            return 0;
        }

        // Count evaluations where deadline has not yet passed AND not yet completed by this member
        return Evaluation::whereDate('interview_date', '>=', today())
            ->whereDoesntHave('nbcAssignments', function ($assignmentQuery) use ($nbcCommittee) {
                $assignmentQuery->where('nbc_committee_id', $nbcCommittee->id)
                    ->where('status', 'complete');
            })
            ->count();
    }

    public function getCompleteTodayCountProperty()
    {
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        if (!$nbcCommittee) {
            return 0;
        }

        // Count evaluations that this member has already completed (regardless of deadline)
        return Evaluation::whereHas('nbcAssignments', function ($assignmentQuery) use ($nbcCommittee) {
                $assignmentQuery->where('nbc_committee_id', $nbcCommittee->id)
                    ->where('status', 'complete');
            })
            ->count();
    }

    /**
     * Print the NBC evaluation report for all active evaluations (deadline not yet passed).
     * Shows all applicants (pending and complete) sorted by last name.
     */
    public function printReport()
    {
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        $evaluations = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])
        ->whereDate('interview_date', '>=', today())
        ->get()
        ->sortBy(fn ($e) => strtolower($e->jobApplication->applicant->last_name))
        ->values();

        if ($evaluations->isEmpty()) {
            session()->flash('print_error', 'No applicants available for evaluation.');
            return;
        }

        $reportData = $evaluations->map(function ($evaluation, $index) use ($nbcCommittee) {
            $a = $evaluation->jobApplication->applicant;
            $p = $evaluation->jobApplication->position;

            // Format name as "LAST, First M."
            $middleInitial = $a->middle_name
                ? ' ' . strtoupper(substr($a->middle_name, 0, 1)) . '.'
                : '';
            $fullName = strtoupper($a->last_name) . ', ' . $a->first_name . $middleInitial;

            // Check assignment status for this NBC member
            $assignment = NbcAssignment::where('evaluation_id', $evaluation->id)
                ->where('nbc_committee_id', $nbcCommittee->id)
                ->first();

            $status = ($assignment && $assignment->status === 'complete') ? 'Complete' : 'Pending';

            return [
                'number'   => $index + 1,
                'name'     => $fullName,
                'position' => $p->name ?? 'N/A',
                'email'    => $a->user->email ?? 'N/A',
                'status'   => $status,
            ];
        })->toArray();

        $totalApplicants = count($reportData);
        $completedCount  = collect($reportData)->where('status', 'Complete')->count();
        $pendingCount    = $totalApplicants - $completedCount;

        $html = view('print.nbc-report', [
            'reportData'      => $reportData,
            'interviewDate'   => today()->format('F d, Y'),
            'totalApplicants' => $totalApplicants,
            'completedCount'  => $completedCount,
            'pendingCount'    => $pendingCount,
            'generatedDate'   => now()->format('F d, Y'),
        ])->render();

        $this->dispatch('openPrintTab', html: $html);
    }

    public function render()
    {
        return view('livewire.nbc.dashboard', [
            'evaluations'        => $this->evaluations,
            'pendingTodayCount'  => $this->pendingTodayCount,
            'completeTodayCount' => $this->completeTodayCount,
        ]);
    }
}