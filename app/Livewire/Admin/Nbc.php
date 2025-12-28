<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Applicant;
use App\Models\NbcAssignment;
use App\Models\EducationalQualification;
use App\Models\ExperienceService;
use App\Models\ProfessionalDevelopment;
use Barryvdh\DomPDF\Facade\Pdf;

class Nbc extends Component
{
    public $searchTerm = '';
    public $selectedPosition = null;
    public $positions = [];
    public $nbcData = [];
    public $applicantId = null;
    public $showSearchModal = false;
    public $tempSearchTerm = '';
    public $tempSelectedPosition = null;
    public $searchResults = [];
    public $showDropdown = false;
    public $selectedApplicantId = null;

    /**
     * Open search modal
     */
    public function openSearchModal()
    {
        $this->showSearchModal = true;
        $this->tempSearchTerm = '';
        $this->tempSelectedPosition = null;
        $this->searchResults = [];
        $this->showDropdown = false;
        $this->selectedApplicantId = null;
        $this->positions = [];
    }

    /**
     * Close search modal
     */
    public function closeSearchModal()
    {
        $this->showSearchModal = false;
    }

    /**
     * Load positions based on temporary search term
     */
    public function updatedTempSearchTerm()
    {
        $this->searchForApplicants();
    }

    /**
     * Search for applicants based on input
     */
    public function searchForApplicants()
    {
        $this->searchResults = [];
        $this->showDropdown = false;
        $this->positions = [];
        $this->tempSelectedPosition = null;
        $this->selectedApplicantId = null;

        if (strlen($this->tempSearchTerm) < 2) {
            return;
        }

        $search = strtolower($this->tempSearchTerm);

        // Find applicants by user name
        $this->searchResults = Applicant::with('user')
            ->whereHas('user', function ($q) use ($search) {
                $q->whereRaw("LOWER(name) LIKE ?", ["%{$search}%"]);
            })
            ->limit(10)
            ->get()
            ->map(function ($applicant) {
                return [
                    'id' => $applicant->id,
                    'full_name' => $applicant->user->name,
                ];
            })
            ->toArray();

        $this->showDropdown = count($this->searchResults) > 0;
    }

    /**
     * Select an applicant from dropdown
     */
    public function selectApplicant($applicantId, $applicantName)
    {
        $this->selectedApplicantId = $applicantId;
        $this->tempSearchTerm = $applicantName;
        $this->showDropdown = false;
        $this->searchResults = [];

        $this->loadPositionsForSelectedApplicant();
    }

    /**
     * Load positions for the selected applicant
     */
    public function loadPositionsForSelectedApplicant()
    {
        $this->positions = [];
        $this->tempSelectedPosition = null;

        if (!$this->selectedApplicantId) {
            return;
        }

        $applicant = Applicant::find($this->selectedApplicantId);

        if (!$applicant) {
            return;
        }

        // Get all positions this applicant has applied for (excluding Instructor I)
        $this->positions = $applicant->jobApplications()
            ->with('position')
            ->where('status', 'approve')
            ->whereHas('position', function ($q) {
                $q->where('name', '!=', 'Instructor I');
            })
            ->get()
            ->pluck('position')
            ->unique('id')
            ->map(function ($position) {
                return [
                    'id' => $position->id,
                    'name' => $position->name,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Perform search from modal
     */
    public function performSearch()
    {
        // Validate that both fields are filled
        if (empty($this->tempSearchTerm) || empty($this->tempSelectedPosition)) {
            session()->flash('error', 'Please fill in both applicant name and position.');
            return;
        }

        // Validate that an applicant was actually selected from dropdown
        if (!$this->selectedApplicantId) {
            session()->flash('error', 'Please select a valid applicant from the dropdown list.');
            return;
        }

        $this->searchTerm = $this->tempSearchTerm;
        $this->selectedPosition = $this->tempSelectedPosition;
        $this->applicantId = $this->selectedApplicantId;

        $this->loadNbcData();

        // Check if data was loaded successfully
        if (empty($this->nbcData)) {
            session()->flash('error', 'No evaluation data found for this applicant and position.');
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
        $this->positions = [];
        $this->nbcData = [];
        $this->applicantId = null;
        $this->tempSearchTerm = '';
        $this->tempSelectedPosition = null;
        $this->searchResults = [];
        $this->showDropdown = false;
        $this->selectedApplicantId = null;
    }

    /**
     * Load NBC data and compute scores
     */
    public function loadNbcData()
    {
        if (!$this->applicantId || empty($this->selectedPosition)) {
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
                ->where('position_id', $this->selectedPosition)
                ->where('status', 'approve');
        })->first();

        if (!$evaluation) {
            $this->nbcData = [];
            return;
        }

        $nbcAssignment = NbcAssignment::where('evaluation_id', $evaluation->id)
            ->where('type', 'evaluate')
            ->first();

        if (!$nbcAssignment) {
            $this->nbcData = [];
            return;
        }

        $educationalQualification = EducationalQualification::find($nbcAssignment->educational_qualification_id);
        $experienceService = ExperienceService::find($nbcAssignment->experience_service_id);
        $professionalDevelopment = ProfessionalDevelopment::find($nbcAssignment->professional_development_id);

        /* ===============================
       1. EDUCATIONAL QUALIFICATION
       =============================== */
        $rsEducation =
            ($educationalQualification->rs_1_1 ?? 0) +
            ($educationalQualification->rs_1_2 ?? 0) +
            ($educationalQualification->rs_1_3 ?? 0);

        $totalEducation = min($educationalQualification->subtotal ?? $rsEducation, 90);

        /* ===============================
       2. EXPERIENCE & SERVICE
       =============================== */
        $rsExperience =
            ($experienceService->rs_2_1_1 ?? 0) +
            ($experienceService->rs_2_1_2 ?? 0) +
            ($experienceService->rs_2_2_1 ?? 0) +
            ($experienceService->rs_2_3_1 ?? 0) +
            ($experienceService->rs_2_3_2 ?? 0);

        $totalExperience = min($experienceService->subtotal ?? $rsExperience, 25);

        /* ===============================
       3. PROFESSIONAL DEVELOPMENT
       (RS ONLY — MAX 90)
       =============================== */
        $rsFields = [
            'rs_3_1_1',
            'rs_3_1_2_a',
            'rs_3_1_2_c',
            'rs_3_1_2_d',
            'rs_3_1_2_e',
            'rs_3_1_2_f',
            'rs_3_1_3_a',
            'rs_3_1_3_b',
            'rs_3_1_3_c',
            'rs_3_1_4',
            'rs_3_2_1_1_a',
            'rs_3_2_1_1_b',
            'rs_3_2_1_1_c',
            'rs_3_2_1_2',
            'rs_3_2_1_3_a',
            'rs_3_2_1_3_b',
            'rs_3_2_1_3_c',
            'rs_3_2_2_1_a',
            'rs_3_2_2_1_b',
            'rs_3_2_2_1_c',
            'rs_3_2_2_2',
            'rs_3_2_2_3',
            'rs_3_2_2_4',
            'rs_3_2_2_5',
            'rs_3_2_2_6',
            'rs_3_2_2_7',
            'rs_3_3_1_a',
            'rs_3_3_1_b',
            'rs_3_3_1_c',
            'rs_3_3_2',
            'rs_3_3_3_a_doctorate',
            'rs_3_3_3_a_masters',
            'rs_3_3_3_a_nondegree',
            'rs_3_3_3_b_doctorate',
            'rs_3_3_3_b_masters',
            'rs_3_3_3_b_nondegree',
            'rs_3_3_3_c_doctorate',
            'rs_3_3_3_c_masters',
            'rs_3_3_3_c_nondegree',
            'rs_3_3_3_d_doctorate',
            'rs_3_3_3_d_masters',
            'rs_3_3_3_e',
            'rs_3_4_a',
            'rs_3_4_b',
            'rs_3_4_c',
            'rs_3_5_1',
            'rs_3_6_1_a',
            'rs_3_6_1_b',
            'rs_3_6_1_c',
            'rs_3_6_1_d'
        ];

        $rsProfessional = 0;
        if ($professionalDevelopment) {
            foreach ($rsFields as $field) {
                $rsProfessional += (float) ($professionalDevelopment->$field ?? 0);
            }
        }

        $totalProfessional = min($rsProfessional, 90);

        /* ===============================
       TOTALS
       =============================== */
        $additionalTotal = $rsEducation + $rsExperience + $rsProfessional;
        $grandTotal = $totalEducation + $totalExperience + $totalProfessional;

        $position = $applicant->jobApplications()
            ->where('position_id', $this->selectedPosition)
            ->first()
            ->position;

        /* ===============================
       FINAL DATA (ALL KEYS DEFINED)
       =============================== */
        $this->nbcData = [[
            'evaluation_id' => $evaluation->id,
            'name' => trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}"),
            'position' => $position->name,
            'college' => $position->college,
            'interview_date' => $evaluation->interview_date,

            // Previous (no data yet, MUST exist)
            'previous_education' => 0,
            'previous_experience' => 0,
            'previous_professional' => 0,
            'previous_total' => 0,

            // Additional (RS)
            'additional_education' => round($rsEducation, 2),
            'additional_experience' => round($rsExperience, 2),
            'additional_professional' => round($rsProfessional, 2),
            'additional_total' => round($additionalTotal, 2),

            // EP (NOT USED — ZEROED BUT REQUIRED BY VIEW)
            'ep_education_subtotal' => 0,
            'ep_experience_subtotal' => 0,
            'ep_professional_subtotal' => 0,
            'ep_total_subtotal' => 0,

            // Final totals
            'total_education' => round($totalEducation, 2),
            'total_experience' => round($totalExperience, 2),
            'total_professional' => round($totalProfessional, 2),
            'grand_total' => round($grandTotal, 2),
            'projected_points' => round($grandTotal, 2),
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
            'data' => $data,
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
