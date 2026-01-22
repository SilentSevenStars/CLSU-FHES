<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Representative;
use App\Models\Position;
use Barryvdh\DomPDF\Facade\Pdf;

class Screening extends Component
{
    public $selectedPosition = null;
    public $selectedDate = null;
    public $searchTerm = '';
    public $positions = [];
    public $interviewDates = [];
    public $screeningData = [];

    // Define allowed faculty positions in the correct order
    private $allowedPositions = [
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

    public function mount()
    {
        $this->loadPositions();
    }

    /**
     * Load unique positions (faculty only) - in predefined order
     */
    public function loadPositions()
    {
        // Get positions that have approved applications with evaluations
        $existingPositions = Position::whereIn('name', $this->allowedPositions)
            ->whereHas('jobApplications', function ($q) {
                $q->where('status', 'approve')
                    ->whereHas('evaluation');
            })
            ->get()
            ->pluck('name')
            ->unique()
            ->toArray();

        // Sort by the predefined order
        $this->positions = collect($this->allowedPositions)
            ->filter(function ($positionName) use ($existingPositions) {
                return in_array($positionName, $existingPositions);
            })
            ->values()
            ->toArray();
    }

    /**
     * Load unique interview dates for the selected position name
     */
    public function updatedSelectedPosition()
    {
        $this->selectedDate = null;
        $this->screeningData = [];

        if (!$this->selectedPosition) {
            $this->interviewDates = [];
            return;
        }

        // Get all interview dates for this position name across all colleges/departments
        $this->interviewDates = Evaluation::whereHas('jobApplication', function ($q) {
            $q->where('status', 'approve')
                ->whereHas('position', function ($posQuery) {
                    $posQuery->where('name', $this->selectedPosition);
                });
        })
            ->whereNotNull('interview_date')
            ->get()
            ->pluck('interview_date')
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    public function updatedSelectedDate()
    {
        $this->loadScreeningData();
    }

    public function updatedSearchTerm()
    {
        $this->loadScreeningData();
    }

    /**
     * Load screening data + compute weighted scores
     */
    public function loadScreeningData()
    {
        if (!$this->selectedPosition || !$this->selectedDate) {
            $this->screeningData = [];
            return;
        }

        // Load evaluations for ALL positions with this name (across all colleges/departments)
        $evaluations = Evaluation::with([
            'jobApplication.applicant',
            'jobApplication.position',
            'panelAssignments.interview',
            'panelAssignments.experience',
            'panelAssignments.performance'
        ])
            ->where('interview_date', $this->selectedDate)
            ->whereHas('jobApplication', function ($q) {
                $q->where('status', 'approve')
                    ->whereHas('position', function ($posQuery) {
                        $posQuery->where('name', $this->selectedPosition);
                    });
            })
            ->get();

        // Include only completed assignments
        $evaluations = $evaluations->filter(function ($evaluation) {
            if ($evaluation->panelAssignments->isEmpty()) {
                return false;
            }

            return $evaluation->panelAssignments->every(function ($pa) {
                return strtolower($pa->status) === 'complete';
            });
        });

        // Apply search
        if ($this->searchTerm) {
            $search = strtolower($this->searchTerm);

            $evaluations = $evaluations->filter(function ($evaluation) use ($search) {
                $a = $evaluation->jobApplication->applicant;
                $full = strtolower("$a->first_name $a->middle_name $a->last_name");

                return str_contains($full, $search);
            });
        }

        // Compute scores
        $this->screeningData = $evaluations->map(function ($evaluation) {

            $assignments = $evaluation->panelAssignments;

            // Read scores from related tables
            $avgPerformance = $assignments->pluck('performance.total_score')->filter()->avg() ?? 0;
            $avgExperience  = $assignments->pluck('experience.total_score')->filter()->avg() ?? 0;
            $avgInterview   = $assignments->pluck('interview.total_score')->filter()->avg() ?? 0;

            $total = $avgPerformance + $avgExperience + $avgInterview;

            $a = $evaluation->jobApplication->applicant;
            $p = $evaluation->jobApplication->position;

            return [
                'evaluation_id'          => $evaluation->id,
                'name'                   => "$a->first_name $a->middle_name $a->last_name",
                'department'             => $p->department ?? $p->name,
                'college'                => $p->college ?? '',
                'performance'            => round($avgPerformance, 2),
                'credentials_experience' => round($avgExperience, 2),
                'interview'              => round($avgInterview, 2),
                'total'                  => round($total, 2),
            ];
        })
            ->sortByDesc('total')
            ->values();

        // Save rank + totals in DB
        $this->screeningData = $this->screeningData->transform(function ($row, $index) {
            $rank = $index + 1;

            Evaluation::where('id', $row['evaluation_id'])
                ->update([
                    'total_score' => $row['total'],
                    'rank' => $rank
                ]);

            $row['rank'] = $rank;
            return $row;
        });
    }

    public function export()
    {
        if (empty($this->screeningData) || !$this->selectedPosition || !$this->selectedDate) {
            session()->flash('error', 'No data to export. Please select both position and date.');
            return;
        }

        // Get position name
        $positionName = $this->selectedPosition;

        // Get panel members from Representative model
        $panelMembers = $this->getPanelMembersFromRepresentatives();

        $pdf = Pdf::loadView('pdf.screening-report', [
            'screeningData' => $this->screeningData->toArray(),
            'positionName' => $positionName,
            'college' => 'All Colleges',
            'department' => 'All Departments',
            'interviewDate' => date('M d, Y', strtotime($this->selectedDate)),
            'panelMembers' => $panelMembers,
            'generatedDate' => now()->format('F d, Y'),
        ]);

        // Set to legal size (long bond paper) in landscape orientation
        $pdf->setPaper('legal', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'screening-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getPanelMembersFromRepresentatives()
    {
        // Fetch all representatives
        $representatives = Representative::all();

        // Initialize panel members array
        $panelMembers = [
            'supervising_admin' => 'TBA',
            'fai_president' => 'TBA',
            'glutches_preside' => 'TBA',
            'ranking_faculty' => 'TBA',
            'dean_cass' => 'TBA',
            'dean_cen' => 'TBA',
            'dean_cos' => 'TBA',
            'dean_ced' => 'TBA',
            'dean_cf' => 'TBA',
            'dean_cba' => 'TBA',
            'senior_faculty' => 'TBA',
            'head_dabe' => 'TBA',
            'head_business' => 'TBA',
            'head_ispels' => 'TBA',
            'chairman_fsb' => 'TBA',
            'university_president' => 'TBA',
        ];

        // Map representatives to their positions
        foreach ($representatives as $rep) {
            switch ($rep->position) {
                case 'Member, Supervising Admin. Officer, HRMO':
                    $panelMembers['supervising_admin'] = $rep->name;
                    break;
                case 'Member, FAI President/Representative':
                    $panelMembers['fai_president'] = $rep->name;
                    break;
                case 'Member, GLUTCHES President':
                    $panelMembers['glutches_preside'] = $rep->name;
                    break;
                case 'Member, Ranking Faculty':
                    $panelMembers['ranking_faculty'] = $rep->name;
                    break;
                case 'Dean, CASS':
                    $panelMembers['dean_cass'] = $rep->name;
                    break;
                case 'Dean, CEN Representative':
                    $panelMembers['dean_cen'] = $rep->name;
                    break;
                case 'Dean, COS':
                    $panelMembers['dean_cos'] = $rep->name;
                    break;
                case 'Dean, CED':
                    $panelMembers['dean_ced'] = $rep->name;
                    break;
                case 'Dean, CF':
                    $panelMembers['dean_cf'] = $rep->name;
                    break;
                case 'Dean, CBA':
                    $panelMembers['dean_cba'] = $rep->name;
                    break;
                case 'Senior Faculty':
                    $panelMembers['senior_faculty'] = $rep->name;
                    break;
                case 'Head, Dept DABE, Representative':
                    $panelMembers['head_dabe'] = $rep->name;
                    break;
                case 'Head, Dept Business':
                    $panelMembers['head_business'] = $rep->name;
                    break;
                case 'Head, ISPELS':
                    $panelMembers['head_ispels'] = $rep->name;
                    break;
                case 'Chairman, Faculty Selection Board & VPAA':
                    $panelMembers['chairman_fsb'] = $rep->name;
                    break;
                case 'University President':
                    $panelMembers['university_president'] = $rep->name;
                    break;
            }
        }

        return $panelMembers;
    }

    public function render()
    {
        return view('livewire.admin.screening');
    }
}