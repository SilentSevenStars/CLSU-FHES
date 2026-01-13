<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;
use App\Models\NbcCommittee;
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
        // Define allowed positions
        $allowedPositions = [
            'Professor III',
            'Professor IV',
            'Professor V',
            'Professor VI',
            'College/University Professor'
        ];

        $query = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])
        // Filter by today's interview date
        ->whereDate('interview_date', today())
        // Filter by allowed positions
        ->whereHas('jobApplication.position', function ($positionQuery) use ($allowedPositions) {
            $positionQuery->whereIn('name', $allowedPositions);
        });

        // Search filter - name or position
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

        // Sort: pending first, complete last
        // Check NBC assignment status for current user
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
        ->orderBy('created_at', 'desc');

        return $query->paginate($this->perPage);
    }

    public function getPendingTodayCountProperty()
    {
        // Define allowed positions
        $allowedPositions = [
            'Professor III',
            'Professor IV',
            'Professor V',
            'Professor VI',
            'College/University Professor'
        ];

        // Get the NBC committee ID for the current user
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();
        
        if (!$nbcCommittee) {
            return 0;
        }

        return Evaluation::whereDate('interview_date', today())
            ->whereHas('jobApplication.position', function ($positionQuery) use ($allowedPositions) {
                $positionQuery->whereIn('name', $allowedPositions);
            })
            ->whereDoesntHave('nbcAssignments', function ($assignmentQuery) use ($nbcCommittee) {
                $assignmentQuery->where('nbc_committee_id', $nbcCommittee->id)
                    ->where('status', 'complete');
            })
            ->count();
    }

    public function getCompleteTodayCountProperty()
    {
        // Define allowed positions
        $allowedPositions = [
            'Professor III',
            'Professor IV',
            'Professor V',
            'Professor VI',
            'College/University Professor'
        ];

        // Get the NBC committee ID for the current user
        $nbcCommittee = NbcCommittee::where('user_id', Auth::id())->first();
        
        if (!$nbcCommittee) {
            return 0;
        }

        return Evaluation::whereDate('interview_date', today())
            ->whereHas('jobApplication.position', function ($positionQuery) use ($allowedPositions) {
                $positionQuery->whereIn('name', $allowedPositions);
            })
            ->whereHas('nbcAssignments', function ($assignmentQuery) use ($nbcCommittee) {
                $assignmentQuery->where('nbc_committee_id', $nbcCommittee->id)
                    ->where('status', 'complete')
                    ->whereDate('updated_at', today());
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.nbc.dashboard', [
            'evaluations' => $this->evaluations,
            'pendingTodayCount' => $this->pendingTodayCount,
            'completeTodayCount' => $this->completeTodayCount,
        ]);
    }
}