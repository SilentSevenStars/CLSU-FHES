<?php

namespace App\Livewire\Nbc;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluation;

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
        $query = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ]);

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
        // Use CASE statement to prioritize pending (null total_score)
        $query->orderByRaw('CASE WHEN total_score IS NULL THEN 0 ELSE 1 END')
              ->orderBy('created_at', 'desc');

        return $query->paginate($this->perPage);
    }

    public function getPendingTodayCountProperty()
    {
        return Evaluation::whereNull('total_score')
            ->whereDate('interview_date', today())
            ->count();
    }

    public function getCompleteTodayCountProperty()
    {
        return Evaluation::whereNotNull('total_score')
            ->whereDate('updated_at', today())
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