<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Applicant;
use App\Models\NbcAssignment;

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

        $this->searchResults = Applicant::with('user')
            ->whereHas('jobApplications', function ($q) {
                $q->where('status', 'approve');
            })
            ->orderBy('last_name')
            ->get()
            ->map(fn($applicant) => [
                'id'        => $applicant->id,
                'full_name' => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
            ])
            ->toArray();
    }

    public function closeSearchModal()
    {
        $this->showSearchModal = false;
    }

    public function updatedTempSearchTerm()
    {
        $this->showDropdown = true;
        $this->selectedApplicantId = null;
        $this->positions = [];
        $this->interviewDates = [];
        $this->tempSelectedPosition = null;
        $this->tempSelectedInterviewDate = null;

        $search = strtolower(trim($this->tempSearchTerm));

        $query = Applicant::with('user')
            ->whereHas('jobApplications', function ($q) {
                $q->where('status', 'approve');
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(first_name) LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("LOWER(last_name) LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("LOWER(middle_name) LIKE ?", ["%{$search}%"]);
            });
        }

        $this->searchResults = $query->orderBy('last_name')
            ->get()
            ->map(fn($applicant) => [
                'id'        => $applicant->id,
                'full_name' => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
            ])
            ->toArray();
    }

    public function showAllApplicants()
    {
        $this->showDropdown = true;
    }

    public function selectApplicant($applicantId, $applicantName)
    {
        $this->selectedApplicantId = $applicantId;
        $this->tempSearchTerm = $applicantName;
        $this->showDropdown = false;

        $this->loadPositionsForSelectedApplicant();
    }

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
            ->whereHas('position', fn($q) => $q->where('name', $this->tempSelectedPosition))
            ->whereHas('evaluation', fn($q) => $q->whereHas('nbcAssignments', fn($a) => $a->where('status', 'complete')))
            ->get()
            ->pluck('evaluation.interview_date')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

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

        $this->searchTerm            = $this->tempSearchTerm;
        $this->selectedPosition      = $this->tempSelectedPosition;
        $this->selectedInterviewDate = $this->tempSelectedInterviewDate;
        $this->applicantId           = $this->selectedApplicantId;

        $this->loadNbcData();

        if (empty($this->nbcData)) {
            session()->flash('error', 'No evaluation data found for this applicant, position, and interview date.');
            return;
        }

        $this->closeSearchModal();
    }

    public function clearSearch()
    {
        $this->searchTerm            = '';
        $this->selectedPosition      = null;
        $this->selectedInterviewDate = null;
        $this->positions             = [];
        $this->interviewDates        = [];
        $this->nbcData               = [];
        $this->applicantId           = null;
        $this->tempSearchTerm        = '';
        $this->tempSelectedPosition  = null;
        $this->tempSelectedInterviewDate = null;
        $this->searchResults         = [];
        $this->showDropdown          = false;
        $this->selectedApplicantId   = null;
    }

    /**
     * Resolve component subtotals from the best nbc_assignment for a given evaluation.
     */
    protected function resolveComponentScores(int $evaluationId, ?string $beforeDate = null): ?array
    {
        $query = NbcAssignment::with([
                'educationalQualification',
                'experienceService',
                'professionalDevelopment',
            ])
            ->where('evaluation_id', $evaluationId)
            ->where('status', 'complete')
            ->whereNotNull('educational_qualification_id')
            ->whereNotNull('experience_service_id')
            ->whereNotNull('professional_development_id');

        if ($beforeDate) {
            $query->where('evaluation_date', '<', $beforeDate);
        }

        $assignment = $query->orderBy('evaluation_date', 'desc')->first();

        if (!$assignment) {
            return null;
        }

        $education    = (float) ($assignment->educationalQualification->subtotal ?? 0);
        $experience   = (float) ($assignment->experienceService->subtotal ?? 0);
        $professional = (float) ($assignment->professionalDevelopment->subtotal ?? 0);
        $total        = $education + $experience + $professional;

        return [
            'evaluation_date' => $assignment->evaluation_date,
            'education'       => round($education, 2),
            'experience'      => round($experience, 2),
            'professional'    => round($professional, 2),
            'total'           => round($total, 2),
        ];
    }

    /**
     * Load NBC data and compute previous / additional / total points.
     */
    public function loadNbcData()
    {
        $this->nbcData = [];

        if (!$this->applicantId || empty($this->selectedPosition) || empty($this->selectedInterviewDate)) {
            return;
        }

        $applicant = Applicant::with(['jobApplications.position'])->find($this->applicantId);

        if (!$applicant) {
            return;
        }

        $evaluation = Evaluation::whereHas('jobApplication', function ($q) use ($applicant) {
                $q->where('applicant_id', $applicant->id)
                  ->where('status', 'approve')
                  ->whereHas('position', fn($posQ) => $posQ->where('name', $this->selectedPosition));
            })
            ->where('interview_date', $this->selectedInterviewDate)
            ->first();

        if (!$evaluation) {
            return;
        }

        $currentScores = $this->resolveComponentScores($evaluation->id);

        if (!$currentScores) {
            return;
        }

        $currentEvaluationDate = $currentScores['evaluation_date'];

        $allEvaluationIds = Evaluation::whereHas('jobApplication', function ($q) use ($applicant) {
                $q->where('applicant_id', $applicant->id)
                  ->where('status', 'approve');
            })
            ->pluck('id');

        $previousAssignment = NbcAssignment::with([
                'educationalQualification',
                'experienceService',
                'professionalDevelopment',
            ])
            ->whereIn('evaluation_id', $allEvaluationIds)
            ->where('status', 'complete')
            ->whereNotNull('educational_qualification_id')
            ->whereNotNull('experience_service_id')
            ->whereNotNull('professional_development_id')
            ->where('evaluation_date', '<', $currentEvaluationDate)
            ->orderBy('evaluation_date', 'desc')
            ->first();

        $previousScores         = null;
        $previousEvaluationDate = null;

        if ($previousAssignment) {
            $prevEducation    = (float) ($previousAssignment->educationalQualification->subtotal ?? 0);
            $prevExperience   = (float) ($previousAssignment->experienceService->subtotal ?? 0);
            $prevProfessional = (float) ($previousAssignment->professionalDevelopment->subtotal ?? 0);
            $prevTotal        = $prevEducation + $prevExperience + $prevProfessional;

            $previousScores = [
                'education'    => round($prevEducation, 2),
                'experience'   => round($prevExperience, 2),
                'professional' => round($prevProfessional, 2),
                'total'        => round($prevTotal, 2),
            ];

            $previousEvaluationDate = $previousAssignment->evaluation_date;
        }

        $additionalEducation    = $currentScores['education']    - ($previousScores['education']    ?? 0);
        $additionalExperience   = $currentScores['experience']   - ($previousScores['experience']   ?? 0);
        $additionalProfessional = $currentScores['professional'] - ($previousScores['professional'] ?? 0);
        $additionalTotal        = $currentScores['total']        - ($previousScores['total']        ?? 0);

        $positionApplication = $applicant->jobApplications()
            ->whereHas('position', fn($q) => $q->where('name', $this->selectedPosition))
            ->first();

        $position     = $positionApplication ? $positionApplication->position : null;
        $positionName = $position ? $position->name : '';
        $collegeName  = $position && $position->college ? $position->college->name : '';

        $this->nbcData = [[
            'evaluation_id'           => $evaluation->id,
            'name'                    => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
            'position'                => $positionName,
            'college'                 => $collegeName,
            'interview_date'          => $evaluation->interview_date,
            'previous_interview_date' => $previousEvaluationDate,

            'previous_education'    => $previousScores ? $previousScores['education']    : '',
            'previous_experience'   => $previousScores ? $previousScores['experience']   : '',
            'previous_professional' => $previousScores ? $previousScores['professional'] : '',
            'previous_total'        => $previousScores ? $previousScores['total']        : '',

            'additional_education'    => round($additionalEducation, 2),
            'additional_experience'   => round($additionalExperience, 2),
            'additional_professional' => round($additionalProfessional, 2),
            'additional_total'        => round($additionalTotal, 2),

            'total_education'    => $currentScores['education'],
            'total_experience'   => $currentScores['experience'],
            'total_professional' => $currentScores['professional'],
            'grand_total'        => $currentScores['total'],
            'projected_points'   => $currentScores['total'],
        ]];
    }

    /**
     * Print — renders the NBC report blade to HTML and opens it in a new tab.
     * Same approach as the Screening component (dispatch openPrintTab).
     */
    public function print()
    {
        if (empty($this->nbcData)) {
            session()->flash('error', 'No data to print. Please search for an applicant first.');
            return;
        }

        // reuse the existing PDF blade which is tailored for a single applicant's
        // NBC report. this mirrors the export() method but renders HTML so it
        // can be opened in a new browser tab for printing.
        $html = view('pdf.nbc-report', [
            'data'          => $this->nbcData[0],
            'generatedDate' => now()->format('F d, Y'),
        ])->render();

        $this->dispatch('openPrintTab', html: $html);
    }

    public function render()
    {
        return view('livewire.admin.nbc');
    }
}