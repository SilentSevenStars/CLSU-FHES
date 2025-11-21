<?php

namespace App\Livewire\Panel;

use App\Models\Evaluation;
use App\Models\Experience as ModelsExperience;
use App\Models\PanelAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Experience extends Component
{
    public $evaluationId;
    public $evaluation;
    
    public $education_qualification = 0; 
    public $experience_type = ''; 
    public $licensure_examination = '';
    public $passing_licensure_examination = '';
    public $place_board_exam = ''; 
    public $professional_activities = 0; 
    public $academic_performance = ''; 
    public $publication = ''; 
    public $school_graduate = ''; 
    
    public $totalScore = 0;

    public $experienceTypeOptions = [
        0 => 'None',
        5 => '1 year',
        10 => '2 years',
        15 => '3 years',
        20 => '4 years',
        25 => '5 years or more',
    ];

    public $licensureOptions = [
        0 => 'None',
        3 => 'Has Licensure',
        5 => 'Has Licensure with Distinction',
    ];

    public $passingLicensureOptions = [
        0 => 'None',
        3 => 'Passed NC II',
        5 => 'Passed NC III or higher',
    ];

    public $placeBoardExamOptions = [
        0 => 'Not Applicable',
        1 => '10th Place',
        2 => '9th Place',
        3 => '8th Place',
        4 => '7th Place',
        5 => '6th Place',
        6 => '5th Place',
        7 => '4th Place',
        8 => '3rd Place',
        9 => '2nd Place',
        10 => '1st Place',
    ];

    public $academicPerformanceOptions = [
        0 => 'None',
        2 => 'Fair',
        4 => 'Satisfactory',
        6 => 'Good',
        8 => 'Very Good',
        10 => 'Outstanding',
    ];

    public $publicationOptions = [
        0 => 'None',
        2 => '1 Publication',
        4 => '2 Publications',
        6 => '3 Publications',
        8 => '4 Publications',
        10 => '5 or more Publications',
    ];

    public $schoolGraduateOptions = [
        0 => 'Not Applicable',
        5 => 'State University/College',
        10 => 'Top 10 National University',
        15 => 'Top 5 National University',
    ];

    public function mount($evaluationId)
    {
        $this->evaluationId = $evaluationId;
        $this->evaluation = Evaluation::findOrFail($evaluationId);

        // Get current logged-in panel
        $user = Auth::user();
        $panel = $user->panel;

        if ($panel) {
            // Create or get PanelAssignment for this panel and evaluation
            PanelAssignment::updateOrCreate(
                [
                    'panel_id' => $panel->id,
                    'evaluation_id' => $evaluationId
                ],
                [
                    'status' => 'not yet'
                ]
            );
        }
    }

    public function updated($propertyName)
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalScore = 
            intval($this->education_qualification) +
            intval($this->experience_type) +
            intval($this->licensure_examination) +
            intval($this->passing_licensure_examination) +
            intval($this->place_board_exam) +
            intval($this->professional_activities) +
            intval($this->academic_performance) +
            intval($this->publication) +
            intval($this->school_graduate);
    }

    public function saveExperience()
    {
        $this->validate([
            'education_qualification' => 'required|numeric|min:0|max:85',
            'experience_type' => 'required|numeric',
            'licensure_examination' => 'required|numeric',
            'passing_licensure_examination' => 'required|numeric',
            'place_board_exam' => 'required|numeric',
            'professional_activities' => 'required|numeric|min:0|max:15',
            'academic_performance' => 'required|numeric',
            'publication' => 'required|numeric',
            'school_graduate' => 'required|numeric',
        ], [
            'education_qualification.required' => 'Please enter Educational Qualification points',
            'education_qualification.max' => 'Educational Qualification cannot exceed 85 points',
            'experience_type.required' => 'Please select Experience Type',
            'licensure_examination.required' => 'Please select Licensure Examination status',
            'passing_licensure_examination.required' => 'Please select Passing Licensure Examination status',
            'place_board_exam.required' => 'Please select Place in Board Examination',
            'professional_activities.required' => 'Please enter Professional Activities points',
            'professional_activities.max' => 'Professional Activities cannot exceed 15 points',
            'academic_performance.required' => 'Please select Academic Performance',
            'publication.required' => 'Please select Publications',
            'school_graduate.required' => 'Please select School Graduated from',
        ]);

        $this->calculateTotal();

        $experience = ModelsExperience::create([
            'education_qualification' => $this->education_qualification,
            'experience_type' => $this->experience_type,
            'licensure_examination' => $this->licensure_examination,
            'passing_licensure_examination' => $this->passing_licensure_examination,
            'place_board_exam' => $this->place_board_exam,
            'professional_activities' => $this->professional_activities,
            'academic_performance' => $this->academic_performance,
            'publication' => $this->publication,
            'school_graduate' => $this->school_graduate,
            'total_score' => $this->totalScore,
        ]);

        $user = Auth::user();
        $panel = $user->panel;
        if ($panel) {
            $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
                ->where('evaluation_id', $this->evaluationId)
                ->first();
            if ($panelAssignment) {
                $panelAssignment->update([
                    'status' => 'complete',
                    'experience_id' => $experience->id,
                ]);
            }
        }

        session()->flash('message', 'Experience evaluation saved successfully.');
        
        // Redirect to dashboard or next section
        return redirect()->route('panel.dashboard');
    }

    public function render()
    {
        return view('livewire.panel.experience')
            ->layout('layouts.app');
    }
}
