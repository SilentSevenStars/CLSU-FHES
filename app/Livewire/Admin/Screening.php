<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Panel;
use Barryvdh\DomPDF\Facade\Pdf;

class Screening extends Component
{
    public $selectedPosition = null;
    public $searchTerm = '';
    public $positions = [];
    public $screeningData = [];

    public function mount()
    {
        $this->loadPositions();
    }

    /**
     * Load unique (Position + Interview Date) combinations
     */
    public function loadPositions()
    {
        $this->positions = Evaluation::with(['jobApplication.position'])
            ->whereHas('jobApplication', fn($q) => $q->where('status', 'approve'))
            ->get()
            ->groupBy(fn($e) => $e->jobApplication->position->id . '_' . $e->interview_date)
            ->map(function ($group) {
                $evaluation = $group->first();
                $position = $evaluation->jobApplication->position;

                return [
                    'id'             => $position->id,
                    'name'           => $position->name,
                    'college'        => $position->college,
                    'department'     => $position->department,
                    'interview_date' => $evaluation->interview_date,
                    'display_name'   => $position->name . ' (' . date('M d, Y', strtotime($evaluation->interview_date)) . ')',
                    'filter_key'     => $position->id . '_' . $evaluation->interview_date,
                ];
            })
            ->values()
            ->toArray();
    }

    public function updatedSelectedPosition()
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
        if (!$this->selectedPosition) {
            $this->screeningData = [];
            return;
        }

        $positionData = collect($this->positions)
            ->firstWhere('filter_key', $this->selectedPosition);

        if (!$positionData) {
            $this->screeningData = [];
            return;
        }

        // Load evaluations and relationships
        $evaluations = Evaluation::with([
            'jobApplication.applicant',
            'jobApplication.position',
            'panelAssignments.interview',
            'panelAssignments.experience',
            'panelAssignments.performance'
        ])
            ->where('interview_date', $positionData['interview_date'])
            ->whereHas('jobApplication', fn($q) =>
                $q->where('position_id', $positionData['id'])
                  ->where('status', 'approve')
            )
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

            // FIX: read scores from related tables
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
        if (empty($this->screeningData) || !$this->selectedPosition) {
            session()->flash('error', 'No data to export. Please select a position first.');
            return;
        }

        $positionData = collect($this->positions)
            ->firstWhere('filter_key', $this->selectedPosition);

        // Get panel members from database
        $panelMembers = $this->getPanelMembersFromDatabase($positionData);

        $pdf = Pdf::loadView('pdf.screening-report', [
            'screeningData' => $this->screeningData->toArray(),
            'positionName' => $positionData['name'] ?? 'Various Positions',
            'college' => $positionData['college'] ?? '',
            'department' => $positionData['department'] ?? '',
            'interviewDate' => $positionData['interview_date'] ?? now()->format('M d, Y'),
            'panelMembers' => $panelMembers,
            'generatedDate' => now()->format('F d, Y'),
        ]);

        $pdf->setPaper('legal', 'landscape');

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'screening-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function getPanelMembersFromDatabase($positionData)
    {
        // Fetch panels based on college and department
        $panels = Panel::with('user')
            ->where('college', $positionData['college'])
            ->where('department', $positionData['department'])
            ->get();

        // Organize panels by position
        $organized = [
            'deans' => [],
            'heads' => [],
            'seniors' => [],
        ];

        foreach ($panels as $panel) {
            $position = strtolower($panel->panel_position);
            $name = $panel->user->name ?? 'TBA';
            
            if (str_contains($position, 'dean')) {
                $organized['deans'][] = [
                    'name' => $name,
                    'title' => 'Dean, ' . $this->getCollegeAcronym($panel->college)
                ];
            } elseif (str_contains($position, 'head')) {
                $organized['heads'][] = [
                    'name' => $name,
                    'title' => 'Head, Dept ' . $panel->department
                ];
            } elseif (str_contains($position, 'senior')) {
                $organized['seniors'][] = [
                    'name' => $name,
                    'title' => 'Senior Faculty, ' . $panel->department
                ];
            }
        }

        return [
            'supervising_admin' => 'Member, Supervising Admin. Officer, HRMO',
            'fai_president' => 'Member, FAI President/Representative',
            'glutches_preside' => 'Member, GLUTCHES Preside',
            'ranking_faculty' => 'Member, Ranking Faculty',
            'deans' => $organized['deans'],
            'heads' => $organized['heads'],
            'seniors' => $organized['seniors'],
            'chairman_fsb' => 'Chairman, Faculty Selection Board & VPAA',
            'university_president' => 'University President',
        ];
    }

    private function getCollegeAcronym($collegeName)
    {
        $acronyms = [
            'College of Agriculture' => 'CA',
            'College of Arts and Social Sciences' => 'CASS',
            'College of Business and Accountancy' => 'CBA',
            'College of Education' => 'CED',
            'College of Engineering' => 'CEN',
            'College of Fisheries' => 'CF',
            'College of Home Science and Industry' => 'CHSI',
            'College of Science' => 'COS',
            'College of Veterinary Science and Medicine' => 'CVSM',
            'Distance, Open, and Transnational University (DOT-Uni)' => 'DOT-Uni',
        ];

        return $acronyms[$collegeName] ?? $collegeName;
    }

    public function render()
    {
        return view('livewire.admin.screening')
            ->layout('layouts.app');
    }
}