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
     * Instructor I & II → panel-based scoring
     * (interview + experience + performance scores from panel_assignments)
     */
    private $panelBasedPositions = [
        'Instructor I',
        'Instructor II',
    ];

    /**
     * Instructor III and above → NBC-based scoring BY DEFAULT.
     *
     * Exception: Instructor III and Assistant Professor I whose position college
     * is one of PANEL_EXPERIENCE_COLLEGES use panel-based scoring instead
     * (interview + experience from panel_assignments + performance).
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

    /**
     * For Instructor III and Assistant Professor I only:
     * if the position's college is one of these, use panel-based scoring
     * (experience from panel_assignments->experience->total_score)
     * instead of the default NBC scoring.
     */
    private const PANEL_EXPERIENCE_COLLEGES = [
        'College of Engineering',
        'College of Business and Accountancy',
        'College of Veterinary Science and Medicine',
    ];

    /**
     * Positions eligible for the panel-experience override.
     */
    private const PANEL_EXPERIENCE_OVERRIDE_POSITIONS = [
        'Instructor III',
        'Assistant Professor I',
    ];

    private function getAllowedPositions(): array
    {
        return array_merge($this->panelBasedPositions, $this->nbcBasedPositions);
    }

    private function isNbcBased(string $positionName): bool
    {
        return in_array($positionName, $this->nbcBasedPositions);
    }

    /**
     * Determine whether a specific evaluation should use panel-based scoring
     * (instead of the default NBC scoring for its position).
     *
     * Returns true when ALL of:
     *   - Position name is Instructor III or Assistant Professor I
     *   - Position college is one of PANEL_EXPERIENCE_COLLEGES
     */
    private function usesPanelExperienceOverride(Evaluation $evaluation): bool
    {
        $positionName = $evaluation->jobApplication->position->name ?? '';
        $collegeName  = $evaluation->jobApplication->position->college->name ?? '';

        return in_array($positionName, self::PANEL_EXPERIENCE_OVERRIDE_POSITIONS)
            && in_array($collegeName, self::PANEL_EXPERIENCE_COLLEGES);
    }

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
     * Scoring rules:
     *
     * A) Instructor I & II  →  always panel-based:
     *      performance        = avg(panel_assignment.performance.total_score) [complete assignments with performance]
     *      credentials_exp    = avg(panel_assignment.experience.total_score)  [complete assignments with experience]
     *      interview          = avg(panel_assignment.interview.total_score)   [complete assignments with interview]
     *
     * B) Instructor III / Assistant Professor I
     *    + college IN (College of Engineering, College of Business and Accountancy,
     *                  College of Veterinary Science and Medicine)
     *    →  panel-based (same as A above)
     *
     * C) All other NBC-based positions (Instructor III in other colleges,
     *    Assistant Professor II+, Associate Professor, Professor)
     *    →  NBC-based:
     *      performance            = avg(panel_assignment.performance.total_score) [complete with performance]
     *                               *** ALWAYS from panel_assignments for ALL positions ***
     *      credentials_experience = avg(eduQual.subtotal) + avg(expSvc.subtotal) + avg(profDev.subtotal)
     *      interview              = avg(panel_assignment.interview.total_score)   [complete with interview]
     *
     * An applicant is only shown if ALL THREE scores (performance, credentials_experience, interview)
     * are non-null and greater than zero.
     */
    public function loadScreeningData()
    {
        if (!$this->selectedPosition || !$this->selectedDate) {
            $this->screeningData = [];
            return;
        }

        // ── Base query ────────────────────────────────────────────────────────
        $evaluations = Evaluation::with([
            'jobApplication.applicant',
            'jobApplication.position.college',
            'jobApplication.position.department',
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
        $this->screeningData = $evaluations->map(function ($evaluation) {

            $a = $evaluation->jobApplication->applicant;
            $p = $evaluation->jobApplication->position;

            // Determine which scoring strategy applies to THIS evaluation.
            $useNbc           = $this->isNbcBased($p->name ?? '');
            $usePanelOverride = $useNbc && $this->usesPanelExperienceOverride($evaluation);
            $usePanelScoring  = !$useNbc || $usePanelOverride;

            // ── Performance: ALWAYS from panel_assignments->performance->total_score
            //    for ALL positions (panel-based and NBC-based alike).
            $completeWithPerformance = ($evaluation->panelAssignments ?? collect())->filter(
                fn($pa) => Str::lower($pa->status) === 'complete' && $pa->performance !== null
            );

            $performanceScores = $completeWithPerformance
                ->pluck('performance.total_score')
                ->filter(fn($v) => $v !== null);

            $performance = $performanceScores->isNotEmpty()
                ? $performanceScores->avg()
                : null;

            // ── Interview: always from panel_assignments->interview->total_score
            $completeWithInterview = ($evaluation->panelAssignments ?? collect())->filter(
                fn($pa) => Str::lower($pa->status) === 'complete' && $pa->interview !== null
            );

            $interviewScores = $completeWithInterview
                ->pluck('interview.total_score')
                ->filter(fn($v) => $v !== null);

            $avgInterview = $interviewScores->isNotEmpty()
                ? $interviewScores->avg()
                : null;

            // ── Credentials & Experience ──────────────────────────────────────
            if ($usePanelScoring) {
                // Panel-based: experience from panel_assignments->experience->total_score
                $completeWithExperience = ($evaluation->panelAssignments ?? collect())->filter(
                    fn($pa) => Str::lower($pa->status) === 'complete' && $pa->experience !== null
                );

                $experienceScores = $completeWithExperience
                    ->pluck('experience.total_score')
                    ->filter(fn($v) => $v !== null);

                $credentialsExperience = $experienceScores->isNotEmpty()
                    ? $experienceScores->avg()
                    : null;

            } else {
                // NBC-based: credentials & experience from NBC assignments
                $qualifyingNbcAssignments = ($evaluation->nbcAssignments ?? collect())->filter(
                    fn($na) => Str::lower($na->status) === 'complete'
                        && $na->educationalQualification !== null
                        && $na->experienceService !== null
                        && $na->professionalDevelopment !== null
                );

                if ($qualifyingNbcAssignments->isEmpty()) {
                    $credentialsExperience = null;
                } else {
                    $avgEduQual = $qualifyingNbcAssignments
                        ->map(fn($na) => (float) $na->educationalQualification->subtotal)
                        ->avg();

                    $avgExpSvc = $qualifyingNbcAssignments
                        ->map(fn($na) => (float) $na->experienceService->subtotal)
                        ->avg();

                    $avgProfDev = $qualifyingNbcAssignments
                        ->map(fn($na) => (float) $na->professionalDevelopment->subtotal)
                        ->avg();

                    $credentialsExperience = $avgEduQual + $avgExpSvc + $avgProfDev;
                }
            }

            // ── Only include this applicant if ALL three scores exist (not null).
            //    A genuine score of 0 is valid and should still be shown.
            if ($performance === null || $credentialsExperience === null || $avgInterview === null) {
                return null; // will be filtered out below
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
            ->filter() // remove null entries (incomplete applicants)
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