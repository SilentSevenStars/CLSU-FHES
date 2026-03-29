<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Representative;
use App\Models\Position;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Screening extends Component
{
    public $selectedPosition = null;
    public $selectedDate = null;
    public $searchTerm = '';
    public $positions = [];
    public $interviewDates = [];
    public $screeningData = [];
    public $vacancies = null;

    /**
     * Instructor I & II  →  panel-based scoring
     * (experience + performance scores from panel_assignments)
     */
    private $panelBasedPositions = [
        'Instructor I',
        'Instructor II',
    ];

    /**
     * Instructor III and above  →  NBC-based scoring
     */
    private $nbcBasedPositions = [
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

    // Removed: No NBC fallback for Instructor I/II - use panel scoring only

    private function getAllowedPositions(): array
    {
        return array_merge($this->panelBasedPositions, $this->nbcBasedPositions);
    }

    private function isNbcBased(string $positionName): bool
    {
        return in_array($positionName, $this->nbcBasedPositions);
    }

    /**
     * Check if position uses NBC scoring (Instructor III+ only)
     */

    public function mount()
    {
        $this->loadPositions();
    }

    /**
     * Load unique positions (faculty only) - in predefined order.
     * A position only appears if at least one evaluation qualifies under its scoring type.
     */
    public function loadPositions()
    {
        $allowedPositions = $this->getAllowedPositions();

        $existingPositions = Position::whereIn('name', $allowedPositions)
            ->whereHas('jobApplications', function ($q) {
                $q->whereIn('status', ['approve', 'hired'])
                    ->whereHas('evaluation');
            })
            ->get()
            ->pluck('name')
            ->unique()
            ->toArray();

        $this->positions = collect($allowedPositions)
            ->filter(fn($p) => in_array($p, $existingPositions))
            ->values()
            ->toArray();
    }

    /**
     * Load unique interview dates for the selected position.
     */
    public function updatedSelectedPosition()
    {
        $this->selectedDate  = null;
        $this->screeningData = [];

        if (!$this->selectedPosition) {
            $this->interviewDates = [];
            return;
        }

        $this->interviewDates = Evaluation::whereHas('jobApplication', function ($q) {
            $q->whereIn('status', ['approve', 'hired'])
                ->whereHas('position', fn($pq) => $pq->where('name', $this->selectedPosition));
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
     * Load screening data and compute weighted scores.
     *
     * Instructor I & II  → panel-based scoring rules:
     *
     *   INCLUSION RULE:
     *   - The applicant must have at least one panel assignment with status = 'complete'.
     *   - Every complete assignment must have an interview score (interview_id not null).
     *   - Every complete assignment must have a performance score (performance_id not null).
     *   - Experience (experience_id) is done by only ONE panel, so it is optional per
     *     assignment. The applicant is NOT excluded if experience is null — it simply
     *     contributes 0 to the average. The applicant is only excluded if ALL complete
     *     assignments have null experience AND null performance AND null interview.
     *
     *   SCORE COMPUTATION:
     *   - Interview score   → average of interview.total_score across all complete assignments
     *   - Performance score → average of performance.total_score across all complete assignments
     *                         that have a performance record
     *   - Experience score  → average of experience.total_score across all complete assignments
     *                         that have an experience record (may be just one panel)
     *
     * Instructor III+  → NBC-based scoring (unchanged).
     *
     * Archived job applications (archive = 1) are always excluded.
     */
    public function loadScreeningData()
    {
        if (!$this->selectedPosition || !$this->selectedDate) {
            $this->screeningData = [];
            return;
        }

        $useNbc = $this->isNbcBased($this->selectedPosition);

        // ── Base query ────────────────────────────────────────────────────────
        $evaluations = Evaluation::with([
            'jobApplication.applicant',
            'jobApplication.position',
            // Panel-based relations
            'panelAssignments.interview',
            'panelAssignments.experience',
            'panelAssignments.performance',
            // NBC-based relations
            'nbcAssignments.educationalQualification',
            'nbcAssignments.experienceService',
            'nbcAssignments.professionalDevelopment',
        ])
            ->whereDate('interview_date', $this->selectedDate)
            ->whereHas('jobApplication', function ($q) {
                $q->whereIn('status', ['approve', 'hired'])
                    ->whereHas('position', fn($pq) => $pq->where('name', $this->selectedPosition));
            })
            ->get();

//         dd($evaluations->map(fn($e) => [
//     'id' => $e->id,
//     'panel_count' => count($e->panelAssignments),
// ]));

        // Filter: Require at least one qualifying assignment (panel or NBC)
        // TODO: Uncomment if strict filtering needed after testing


        // ── Apply search ──────────────────────────────────────────────────────
        if ($this->searchTerm) {
            $search = strtolower($this->searchTerm);

            $evaluations = $evaluations->filter(function ($evaluation) use ($search) {
                $a    = $evaluation->jobApplication->applicant;
                $full = strtolower("{$a->first_name} {$a->middle_name} {$a->last_name}");

                return str_contains($full, $search);
            });
        }

        // ── Compute scores ────────────────────────────────────────────────────
        $this->screeningData = $evaluations->map(function ($evaluation) use ($useNbc) {

            $a = $evaluation->jobApplication->applicant;
            $p = $evaluation->jobApplication->position;

            if ($useNbc) {
                // NBC scoring — only COMPLETE assignments with all three sub-scores
                $qualifyingAssignments = ($evaluation->nbcAssignments ?? collect())->filter(function ($na) {
                    return Str::lower($na->status) === 'complete'
                        && $na->educationalQualification !== null
                        && $na->experienceService !== null
                        && $na->professionalDevelopment !== null;
                });

                $avgEduQual = $qualifyingAssignments->map(fn($na) => (float) $na->educationalQualification->subtotal)->avg() ?? 0;
                $avgExpSvc  = $qualifyingAssignments->map(fn($na) => (float) $na->experienceService->subtotal)->avg() ?? 0;
                $avgProfDev = $qualifyingAssignments->map(fn($na) => (float) $na->professionalDevelopment->subtotal)->avg() ?? 0;

                $credentialsExperience = $avgEduQual + $avgExpSvc + $avgProfDev;
                $performance           = $avgProfDev;

                $qualifyingPanelAssignments = ($evaluation->panelAssignments ?? collect())->filter(function ($pa) {
                    return Str::lower($pa->status) === 'complete' && $pa->interview !== null;
                });
                $avgInterview = $qualifyingPanelAssignments->pluck('interview.total_score')->filter()->avg() ?? 0;

            } else {
                // Panel-based scoring: avg only qualifying assignments (with required scores)
                $qualifyingInterviewPerf = ($evaluation->panelAssignments ?? collect())->filter(function ($pa) {
                    return Str::lower($pa->status) === 'complete';
                }); // Lenient: status complete only, avg available scores (0 if missing)

                $avgInterview = $qualifyingInterviewPerf
                    ->pluck('interview.total_score')
                    ->filter()
                    ->avg() ?? 0;

                $performance = $qualifyingInterviewPerf
                    ->pluck('performance.total_score')
                    ->filter()
                    ->avg() ?? 0;

                // Experience → avg assignments with experience record (lenient)
                $qualifyingExperience = ($evaluation->panelAssignments ?? collect())->filter(function ($pa) {
                    return Str::lower($pa->status) === 'complete';
                }); // 0 if no experience

                $credentialsExperience = $qualifyingExperience
                    ->pluck('experience.total_score')
                    ->filter()
                    ->avg() ?? 0;
            }

            $total = $performance + $credentialsExperience + $avgInterview;

            return [
                'evaluation_id'          => $evaluation->id,
                'name'                   => "{$a->first_name} {$a->middle_name} {$a->last_name}",
                'department'             => $p->department->name ?? $p->name,
                'specialization'         => $p->specialization ?? 'N/A',
                'college'                => $p->college->name ?? '',
                'performance'            => round($performance, 2),
                'credentials_experience' => round($credentialsExperience, 2),
                'interview'              => round($avgInterview, 2),
                'total'                  => round($total, 2),
            ];
        })
            ->sortByDesc('total')
            ->values();

        // ── Persist rank & total in DB ────────────────────────────────────────
        $this->screeningData = $this->screeningData->transform(function ($row, $index) {
            $rank = $index + 1;

            Evaluation::where('id', $row['evaluation_id'])
                ->update([
                    'total_score' => $row['total'],
                    'rank'        => $rank,
                ]);

            $row['rank'] = $rank;
            return $row;
        });
    }

    /**
     * Print — renders the screening report blade to HTML and opens in a new tab.
     */
    public function print()
    {
        if (empty($this->screeningData) || !$this->selectedPosition || !$this->selectedDate) {
            session()->flash('error', 'No data to print. Please select both position and date.');
            return;
        }

        $positionName     = $this->selectedPosition;
        $exportCollection = collect($this->screeningData)->sortBy('rank');

        if ($this->vacancies && $this->vacancies > 0) {
            $exportCollection = $exportCollection->take((int) $this->vacancies);
        }

        $exportData   = $exportCollection->values()->toArray();
        $panelMembers = $this->getPanelMembersFromRepresentatives();
        $rowsPerPage  = 10;

        $html = view('pdf.screening-report', [
            'screeningData' => $exportData,
            'rowsPerPage'   => $rowsPerPage,
            'positionName'  => $positionName,
            'college'       => 'All Colleges',
            'department'    => 'All Departments',
            'interviewDate' => date('M d, Y', strtotime($this->selectedDate)),
            'panelMembers'  => $panelMembers,
            'generatedDate' => now()->format('F d, Y'),
            'vacancies'     => $this->vacancies,
        ])->render();

        $this->dispatch('openPrintTab', html: $html);

        $candidateCount = count($exportData);
        $vacancyNote    = ($this->vacancies && $this->vacancies > 0)
            ? ", limited to {$this->vacancies} vacancy/vacancies"
            : '';

        AccountActivityService::log(
            Auth::user(),
            "Printed screening report — Position: \"{$positionName}\", "
                . "Interview Date: " . date('M d, Y', strtotime($this->selectedDate))
                . ", Candidates printed: {$candidateCount}{$vacancyNote}."
        );
    }

    private function getPanelMembersFromRepresentatives()
    {
        $representatives = Representative::all();

        $panelMembers = [
            'supervising_admin'    => 'TBA',
            'fai_president'        => 'TBA',
            'glutches_preside'     => 'TBA',
            'ranking_faculty'      => 'TBA',
            'dean_cass'            => 'TBA',
            'dean_cen'             => 'TBA',
            'dean_cos'             => 'TBA',
            'dean_ced'             => 'TBA',
            'dean_cf'              => 'TBA',
            'dean_cba'             => 'TBA',
            'senior_faculty'       => 'TBA',
            'head_dabe'            => 'TBA',
            'head_business'        => 'TBA',
            'head_ispels'          => 'TBA',
            'chairman_fsb'         => 'TBA',
            'university_president' => 'TBA',
        ];

        foreach ($representatives as $rep) {
            switch ($rep->position) {
                case 'Member, Supervising Admin. Officer, HRMO':
                    $panelMembers['supervising_admin'] = $rep->name; break;
                case 'Member, FAI President/Representative':
                    $panelMembers['fai_president'] = $rep->name; break;
                case 'Member, GLUTCHES President':
                    $panelMembers['glutches_preside'] = $rep->name; break;
                case 'Member, Ranking Faculty':
                    $panelMembers['ranking_faculty'] = $rep->name; break;
                case 'Dean, CASS':
                    $panelMembers['dean_cass'] = $rep->name; break;
                case 'Dean, CEN Representative':
                    $panelMembers['dean_cen'] = $rep->name; break;
                case 'Dean, COS':
                    $panelMembers['dean_cos'] = $rep->name; break;
                case 'Dean, CED':
                    $panelMembers['dean_ced'] = $rep->name; break;
                case 'Dean, CF':
                    $panelMembers['dean_cf'] = $rep->name; break;
                case 'Dean, CBA':
                    $panelMembers['dean_cba'] = $rep->name; break;
                case 'Senior Faculty':
                    $panelMembers['senior_faculty'] = $rep->name; break;
                case 'Head, Dept DABE, Representative':
                    $panelMembers['head_dabe'] = $rep->name; break;
                case 'Head, Dept Business':
                    $panelMembers['head_business'] = $rep->name; break;
                case 'Head, ISPELS':
                    $panelMembers['head_ispels'] = $rep->name; break;
                case 'Chairman, Faculty Selection Board & VPAA':
                    $panelMembers['chairman_fsb'] = $rep->name; break;
                case 'University President':
                    $panelMembers['university_president'] = $rep->name; break;
            }
        }

        return $panelMembers;
    }

    public function render()
    {
        return view('livewire.admin.screening');
    }
}