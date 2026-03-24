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

        // Fetch all evaluations where deadline has not passed, with all needed relations
        $query = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])
        ->whereDate('interview_date', '>=', today());

        // Sort: pending first, complete last
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
        ', [Auth::id()]);

        // Get all results first so Eloquent casts can decrypt the fields
        $all = $query->get();

        // PHP-side search: name (encrypted via cast), email (encrypted via cast), position (plaintext)
        if ($this->search) {
            $search = strtolower($this->search);
            $all = $all->filter(function ($evaluation) use ($search) {
                $applicant = $evaluation->jobApplication?->applicant;
                $user      = $applicant?->user;

                // full_name may be a computed attribute — also check individual parts
                $firstName = strtolower($applicant?->first_name ?? '');
                $middleName = strtolower($applicant?->middle_name ?? '');
                $lastName  = strtolower($applicant?->last_name ?? '');
                $email     = strtolower($user?->email ?? '');
                $position  = strtolower($evaluation->jobApplication?->position?->name ?? '');

                return str_contains($firstName, $search)
                    || str_contains($middleName, $search)
                    || str_contains($lastName, $search)
                    || str_contains($email, $search)
                    || str_contains($position, $search);
            })->values();
        }

        // Manual pagination on the filtered collection
        $perPage     = (int) $this->perPage;
        $currentPage = $this->getPage();
        $total       = $all->count();
        $items       = $all->forPage($currentPage, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function getPendingTodayCountProperty()
    {
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        if (!$nbcCommittee) {
            return 0;
        }

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

        return Evaluation::whereHas('nbcAssignments', function ($assignmentQuery) use ($nbcCommittee) {
                $assignmentQuery->where('nbc_committee_id', $nbcCommittee->id)
                    ->where('status', 'complete');
            })
            ->count();
    }

    /**
     * Print the NBC evaluation report for all active evaluations (deadline not yet passed).
     */
    public function printReport()
    {
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();

        $evaluations = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
            'nbcAssignments.educationalQualification',
            'nbcAssignments.experienceService',
            'nbcAssignments.professionalDevelopment',
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

            $middleInitial = $a->middle_name
                ? ' ' . strtoupper(substr($a->middle_name, 0, 1)) . '.'
                : '';
            $fullName = strtoupper($a->last_name) . ', ' . $a->first_name . $middleInitial;

            $assignment = NbcAssignment::with([
                'educationalQualification',
                'experienceService',
                'professionalDevelopment',
            ])
            ->where('evaluation_id', $evaluation->id)
            ->where('nbc_committee_id', $nbcCommittee->id)
            ->first();

            $isComplete = $assignment && $assignment->status === 'complete';

            $eduScore  = $isComplete && $assignment->educationalQualification
                ? (float) $assignment->educationalQualification->subtotal
                : 0;
            $expScore  = $isComplete && $assignment->experienceService
                ? (float) $assignment->experienceService->subtotal
                : 0;
            $proScore  = $isComplete && $assignment->professionalDevelopment
                ? (float) $assignment->professionalDevelopment->subtotal
                : 0;
            $totalScore = $eduScore + $expScore + $proScore;

            $evaluationDate = $isComplete && $assignment->evaluation_date
                ? \Carbon\Carbon::parse($assignment->evaluation_date)->format('m/d/Y')
                : null;

            return [
                'number'          => $index + 1,
                'name'            => $fullName,
                'position'        => $p->name ?? 'N/A',
                'email'           => $a->user->email ?? 'N/A',
                'status'          => $isComplete ? 'Complete' : 'Pending',
                'edu_score'       => $isComplete ? number_format($eduScore, 3) : '0.000',
                'exp_score'       => $isComplete ? number_format($expScore, 3) : '0.000',
                'pro_score'       => $isComplete ? number_format($proScore, 3) : '0.000',
                'total_score'     => $isComplete ? number_format($totalScore, 3) : '0.000',
                'evaluation_date' => $evaluationDate,
            ];
        })->toArray();

        $totalApplicants = count($reportData);
        $completedCount  = collect($reportData)->where('status', 'Complete')->count();
        $pendingCount    = $totalApplicants - $completedCount;

        $html = view('print.nbc-shedule-report', [
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