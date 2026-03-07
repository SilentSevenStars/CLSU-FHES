<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Applicant;
use App\Models\NbcAssignment;
use App\Models\Nbc as NbcModel;
use Barryvdh\DomPDF\Facade\Pdf;

class Nbc extends Component
{
    public $searchTerm = '';
    public $selectedPosition = null;
    public $selectedInterviewDate = null;
    public $positions = [];
    public $nbcData = [];
    public $applicantId = null;
    public $showSearchModal = false;
    public $tempSearchTerm = '';
    public $tempSelectedPosition = null;
    public $tempSelectedInterviewDate = null;
    public $searchResults = [];
    public $showDropdown = false;
    public $selectedApplicantId = null;
    public $interviewDates = [];

    /**
     * Open search modal — load ALL applicants immediately.
     */
    public function openSearchModal()
    {
        $this->showSearchModal = true;
        $this->tempSearchTerm = '';
        $this->tempSelectedPosition = null;
        $this->tempSelectedInterviewDate = null;
        $this->showDropdown = false;
        $this->selectedApplicantId = null;
        $this->positions = [];
        $this->interviewDates = [];

        // Load all applicants with at least one approved application right away
        $this->searchResults = Applicant::with('user')
            ->whereHas('jobApplications', function ($q) {
                $q->where('status', 'approve');
            })
            ->orderBy('last_name')
            ->get()
            ->map(function ($applicant) {
                return [
                    'id'        => $applicant->id,
                    'full_name' => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
                ];
            })
            ->toArray();
    }

    /**
     * Close search modal
     */
    public function closeSearchModal()
    {
        $this->showSearchModal = false;
    }

    /**
     * Filter the already-loaded results as the user types.
     * No minimum character requirement — list is always visible.
     */
    public function updatedTempSearchTerm()
    {
        $this->showDropdown = true;
        $this->selectedApplicantId = null;
        $this->positions = [];
        $this->interviewDates = [];
        $this->tempSelectedPosition = null;
        $this->tempSelectedInterviewDate = null;

        $search = strtolower(trim($this->tempSearchTerm));

        if ($search === '') {
            // Show all when input is empty
            $this->searchResults = Applicant::with('user')
                ->whereHas('jobApplications', function ($q) {
                    $q->where('status', 'approve');
                })
                ->orderBy('last_name')
                ->get()
                ->map(function ($applicant) {
                    return [
                        'id'        => $applicant->id,
                        'full_name' => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
                    ];
                })
                ->toArray();
        } else {
            // Filter by typed text
            $this->searchResults = Applicant::with('user')
                ->whereHas('jobApplications', function ($q) {
                    $q->where('status', 'approve');
                })
                ->where(function ($q) use ($search) {
                    $q->whereRaw("LOWER(first_name) LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("LOWER(last_name) LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("LOWER(middle_name) LIKE ?", ["%{$search}%"]);
                })
                ->orderBy('last_name')
                ->get()
                ->map(function ($applicant) {
                    return [
                        'id'        => $applicant->id,
                        'full_name' => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
                    ];
                })
                ->toArray();
        }
    }

    /**
     * Show dropdown when input is focused (called from blade via wire:focus).
     */
    public function showAllApplicants()
    {
        $this->showDropdown = true;
    }

    /**
     * Select an applicant from dropdown
     */
    public function selectApplicant($applicantId, $applicantName)
    {
        $this->selectedApplicantId = $applicantId;
        $this->tempSearchTerm = $applicantName;
        $this->showDropdown = false;

        $this->loadPositionsForSelectedApplicant();
    }

    /**
     * Load ALL positions (no whitelist) for the selected applicant.
     */
    public function loadPositionsForSelectedApplicant()
    {
        $this->positions = [];
        $this->interviewDates = [];
        $this->tempSelectedPosition = null;
        $this->tempSelectedInterviewDate = null;

        if (!$this->selectedApplicantId) {
            return;
        }

        $applicant = Applicant::find($this->selectedApplicantId);

        if (!$applicant) {
            return;
        }

        $this->positions = $applicant->jobApplications()
            ->with('position')
            ->where('status', 'approve')
            ->get()
            ->pluck('position.name')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Load interview dates when position is selected.
     */
    public function updatedTempSelectedPosition()
    {
        $this->interviewDates = [];
        $this->tempSelectedInterviewDate = null;

        if (!$this->selectedApplicantId || !$this->tempSelectedPosition) {
            return;
        }

        $applicant = Applicant::find($this->selectedApplicantId);

        if (!$applicant) {
            return;
        }

        $this->interviewDates = $applicant->jobApplications()
            ->with(['position', 'evaluation'])
            ->where('status', 'approve')
            ->whereHas('position', function ($q) {
                $q->where('name', $this->tempSelectedPosition);
            })
            ->whereHas('evaluation')
            ->get()
            ->pluck('evaluation.interview_date')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Perform search from modal.
     */
    public function performSearch()
    {
        if (empty($this->tempSearchTerm) || empty($this->tempSelectedPosition) || empty($this->tempSelectedInterviewDate)) {
            session()->flash('error', 'Please fill in applicant name, position, and interview date.');
            return;
        }

        if (!$this->selectedApplicantId) {
            session()->flash('error', 'Please select a valid applicant from the dropdown list.');
            return;
        }

        $this->searchTerm = $this->tempSearchTerm;
        $this->selectedPosition = $this->tempSelectedPosition;
        $this->selectedInterviewDate = $this->tempSelectedInterviewDate;
        $this->applicantId = $this->selectedApplicantId;

        $this->loadNbcData();

        if (empty($this->nbcData)) {
            session()->flash('error', 'No evaluation data found for this applicant, position, and interview date.');
            return;
        }

        $this->closeSearchModal();
    }

    /**
     * Clear search
     */
    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->selectedPosition = null;
        $this->selectedInterviewDate = null;
        $this->positions = [];
        $this->interviewDates = [];
        $this->nbcData = [];
        $this->applicantId = null;
        $this->tempSearchTerm = '';
        $this->tempSelectedPosition = null;
        $this->tempSelectedInterviewDate = null;
        $this->searchResults = [];
        $this->showDropdown = false;
        $this->selectedApplicantId = null;
    }

    /**
     * Resolve the best NBC record for a given evaluation_id.
     * Priority: verify assignment > evaluate assignment.
     */
    protected function resolveNbcRecord(int $evaluationId): ?NbcModel
    {
        $verifyAssignment = NbcAssignment::where('evaluation_id', $evaluationId)
            ->where('type', 'verify')
            ->whereNotNull('nbc_id')
            ->first();

        if ($verifyAssignment && $verifyAssignment->nbc) {
            return $verifyAssignment->nbc;
        }

        $evaluateAssignment = NbcAssignment::where('evaluation_id', $evaluationId)
            ->where('type', 'evaluate')
            ->whereNotNull('nbc_id')
            ->first();

        return $evaluateAssignment?->nbc;
    }

    /**
     * Load NBC data and compute scores.
     */
    public function loadNbcData()
    {
        if (!$this->applicantId || empty($this->selectedPosition) || empty($this->selectedInterviewDate)) {
            $this->nbcData = [];
            return;
        }

        $applicant = Applicant::with(['jobApplications.position'])
            ->find($this->applicantId);

        if (!$applicant) {
            $this->nbcData = [];
            return;
        }

        $evaluation = Evaluation::whereHas('jobApplication', function ($q) use ($applicant) {
            $q->where('applicant_id', $applicant->id)
                ->where('status', 'approve')
                ->whereHas('position', function ($posQ) {
                    $posQ->where('name', $this->selectedPosition);
                });
        })
        ->where('interview_date', $this->selectedInterviewDate)
        ->first();

        if (!$evaluation) {
            $this->nbcData = [];
            return;
        }

        $nbcRecord = $this->resolveNbcRecord($evaluation->id);

        if (!$nbcRecord) {
            $this->nbcData = [];
            return;
        }

        $currentEducation    = $nbcRecord->educational_qualification ?? 0;
        $currentExperience   = $nbcRecord->experience ?? 0;
        $currentProfessional = $nbcRecord->professional_development ?? 0;
        $currentTotal        = $nbcRecord->total_score ?? 0;

        $previousEvaluation = Evaluation::whereHas('jobApplication', function ($q) use ($applicant) {
                $q->where('applicant_id', $applicant->id)
                    ->where('status', 'approve');
            })
            ->where('interview_date', '<', $this->selectedInterviewDate)
            ->whereHas('nbcAssignments', function ($q) {
                $q->whereNotNull('nbc_id');
            })
            ->orderBy('interview_date', 'desc')
            ->first();

        $previousNbc           = null;
        $previousInterviewDate = null;

        if ($previousEvaluation) {
            $previousNbc           = $this->resolveNbcRecord($previousEvaluation->id);
            $previousInterviewDate = $previousEvaluation->interview_date;
        }

        $previousEducation    = $previousNbc?->educational_qualification;
        $previousExperience   = $previousNbc?->experience;
        $previousProfessional = $previousNbc?->professional_development;
        $previousTotal        = $previousNbc?->total_score;

        $position = $applicant->jobApplications()
            ->whereHas('position', function ($q) {
                $q->where('name', $this->selectedPosition);
            })
            ->first()
            ->position;

        $this->nbcData = [[
            'evaluation_id'           => $evaluation->id,
            'name'                    => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
            'position'                => $position->name,
            'college'                 => $position->college->name,
            'interview_date'          => $evaluation->interview_date,
            'previous_interview_date' => $previousInterviewDate,

            'previous_education'    => $previousEducation    !== null ? round($previousEducation, 2)    : '',
            'previous_experience'   => $previousExperience   !== null ? round($previousExperience, 2)   : '',
            'previous_professional' => $previousProfessional !== null ? round($previousProfessional, 2) : '',
            'previous_total'        => $previousTotal        !== null ? round($previousTotal, 2)        : '',

            'additional_education'    => round($currentEducation, 2),
            'additional_experience'   => round($currentExperience, 2),
            'additional_professional' => round($currentProfessional, 2),
            'additional_total'        => round($currentTotal, 2),

            'ep_education_subtotal'    => 0,
            'ep_experience_subtotal'   => 0,
            'ep_professional_subtotal' => 0,
            'ep_total_subtotal'        => 0,

            'total_education'    => round($currentEducation, 2),
            'total_experience'   => round($currentExperience, 2),
            'total_professional' => round($currentProfessional, 2),
            'grand_total'        => round($currentTotal, 2),
            'projected_points'   => round($currentTotal, 2),
        ]];
    }

    public function export()
    {
        if (empty($this->nbcData)) {
            session()->flash('error', 'No data to export. Please search for an applicant first.');
            return;
        }

        $data = $this->nbcData[0];

        $pdf = Pdf::loadView('pdf.nbc-report', [
            'data'          => $data,
            'generatedDate' => now()->format('F d, Y'),
        ]);

        $pdf->setPaper('legal', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'nbc-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.nbc');
    }
}