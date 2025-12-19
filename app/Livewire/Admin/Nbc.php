<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Applicant;
use Barryvdh\DomPDF\Facade\Pdf;

class Nbc extends Component
{
    public $searchTerm = '';
    public $selectedPosition = null;
    public $positions = [];
    public $nbcData = [];
    public $applicantId = null;

    public function updatedSearchTerm()
    {
        $this->loadPositionsForApplicant();
        $this->loadNbcData();
    }

    public function updatedSelectedPosition()
    {
        $this->loadNbcData();
    }

    /**
     * Load positions for the searched applicant
     */
    public function loadPositionsForApplicant()
    {
        $this->positions = [];
        $this->selectedPosition = null;
        $this->applicantId = null;

        if (empty($this->searchTerm)) {
            return;
        }

        $search = strtolower($this->searchTerm);

        // Find applicant by name
        $applicant = Applicant::whereRaw("LOWER(CONCAT(first_name, ' ', middle_name, ' ', last_name)) LIKE ?", ["%{$search}%"])
            ->first();

        if (!$applicant) {
            return;
        }

        $this->applicantId = $applicant->id;

        // Get all positions this applicant has applied for (excluding Instructor I)
        $this->positions = $applicant->jobApplications()
            ->with('position')
            ->where('status', 'approve')
            ->whereHas('position', function($q) {
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
     * Load NBC data and compute scores
     */
    public function loadNbcData()
    {
        // Only load if both search term and position are provided
        if (empty($this->searchTerm) || empty($this->selectedPosition)) {
            $this->nbcData = [];
            return;
        }

        if (!$this->applicantId) {
            $this->nbcData = [];
            return;
        }

        $applicant = Applicant::with(['experiences', 'jobApplications.position'])
            ->find($this->applicantId);

        if (!$applicant) {
            $this->nbcData = [];
            return;
        }

        // Get evaluation for this position
        $evaluation = Evaluation::whereHas('jobApplication', function($q) use ($applicant) {
            $q->where('applicant_id', $applicant->id)
              ->where('position_id', $this->selectedPosition)
              ->where('status', 'approve');
        })->first();

        if (!$evaluation) {
            $this->nbcData = [];
            return;
        }

        $experiences = $applicant->experiences;

        // 1.0 Educational Qualification (Max 85) - ONLY education_qualification field
        $educationQualification = $experiences->sum('education_qualification');

        // 2.0 Experience and Length of Service (Max 25) - ONLY experience_type field
        $experienceService = $experiences->sum('experience_type');

        // 3.0 Professional Development, Achievement and Honors (Max 90)
        // Sum of ALL OTHER fields except education_qualification and experience_type
        $professionalDevelopment = $experiences->sum(function($exp) {
            return $exp->licensure_examination 
                + $exp->passing_licensure_examination 
                + $exp->place_board_exam
                + $exp->professional_activities 
                + $exp->academic_performance 
                + $exp->publication 
                + $exp->school_graduate;
        });
        
        // Cap at 90
        $professionalDevelopment = min($professionalDevelopment, 90);

        // Previous points (empty for now)
        $previousEducation = 0;
        $previousExperience = 0;
        $previousProfessional = 0;
        $previousTotal = 0;

        // Additional points (current period)
        $additionalEducation = $educationQualification;
        $additionalExperience = $experienceService;
        $additionalProfessional = $professionalDevelopment;
        $additionalTotal = $additionalEducation + $additionalExperience + $additionalProfessional;

        // Total points
        $totalEducation = $previousEducation + $additionalEducation;
        $totalExperience = $previousExperience + $additionalExperience;
        $totalProfessional = $previousProfessional + $additionalProfessional;
        $grandTotal = $previousTotal + $additionalTotal;

        $position = $applicant->jobApplications()
            ->where('position_id', $this->selectedPosition)
            ->first()
            ->position;

        $this->nbcData = [[
            'evaluation_id' => $evaluation->id,
            'name' => "$applicant->first_name $applicant->middle_name $applicant->last_name",
            'position' => $position->name,
            'college' => $position->college,
            'interview_date' => $evaluation->interview_date,
            'previous_education' => round($previousEducation, 2),
            'previous_experience' => round($previousExperience, 2),
            'previous_professional' => round($previousProfessional, 2),
            'previous_total' => round($previousTotal, 3),
            'additional_education' => round($additionalEducation, 2),
            'additional_experience' => round($additionalExperience, 2),
            'additional_professional' => round($additionalProfessional, 2),
            'additional_total' => round($additionalTotal, 3),
            'total_education' => round($totalEducation, 2),
            'total_experience' => round($totalExperience, 2),
            'total_professional' => round($totalProfessional, 2),
            'grand_total' => round($grandTotal, 3),
            'projected_points' => round($grandTotal, 3),
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

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'nbc-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.nbc');
    }
}