<?php

namespace App\Livewire\Panel;

use App\Models\Evaluation;
use App\Models\Experience as ModelsExperience;
use App\Models\PanelAssignment;
use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Experience extends Component
{
    public $evaluationId;
    public $evaluation;
    public $applicant;
    public $position;
    public $jobApplication;
    
    public $education_qualification = 0; 
    public $experience_type = 0; 
    public $licensure_examination = 0;
    public $place_board_exam = ''; 
    public $professional_activities = 0; 
    public $academic_performance = ''; 
    public $publication = ''; 
    public $school_graduate = ''; 
    
    public $totalScore = 0;
    public $showApplicantModal = false;

    public $placeBoardExamOptions = [
        '' => 'Not Applicable',
        10 => '1st Place',
        8 => '2nd Place',
        5 => '3rd to 20th Place',
    ];

    public $academicPerformanceOptions = [
        '' => 'Select',
        10 => 'Summa Cum Laude',
        8 => 'Magna Cum Laude',
        6 => 'Cum Laude',
        4 => 'Honourable Mention',
        2 => 'No failing grade',
        0 => 'With failing grade',
    ];

    public $publicationOptions = [
        '' => 'Select',
        10 => '7 or more publications in the last 5 years',
        5 => '6 and below',
    ];

    public $schoolGraduateOptions = [
        '' => 'Select',
        15 => 'QS World Rank University',
        10 => 'Reputable foreign University/Partner foreign institution of CLSU/COE',
        8 => 'COD',
        5 => 'Level IV accredited program',
    ];

    public function mount($evaluationId)
    {
        $this->evaluationId = $evaluationId;
        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])->findOrFail($evaluationId);

        $this->jobApplication = $this->evaluation->jobApplication;
        $this->applicant = $this->jobApplication->applicant;
        $this->position = $this->jobApplication->position;

        $user = Auth::user();
        $panel = $user->panel;

        if ($panel) {
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

    public function toggleApplicantModal()
    {
        $this->showApplicantModal = !$this->showApplicantModal;
    }

    /**
     * Generate base64 encoded PDF for viewing in modal
     */
    public function getFileDataUrl()
    {
        $encryptionService = new FileEncryptionService();

        if (!$this->jobApplication->requirements_file || 
            !$encryptionService->fileExists($this->jobApplication->requirements_file)) {
            $this->dispatch('show-error', message: 'File not found.');
            return null;
        }

        try {
            $decryptedContents = $encryptionService->decryptFile($this->jobApplication->requirements_file);
            $base64 = base64_encode($decryptedContents);
            return 'data:application/pdf;base64,' . $base64;
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Error loading file: ' . $e->getMessage());
            return null;
        }
    }

    public function updated($propertyName)
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalScore = 
            floatval($this->education_qualification) +
            floatval($this->experience_type) +
            floatval($this->licensure_examination) +
            floatval($this->place_board_exam) +
            floatval($this->professional_activities) +
            floatval($this->academic_performance) +
            floatval($this->publication) +
            floatval($this->school_graduate);
    }

    public function confirmSubmission()
    {
        $this->dispatch('showSwalConfirm');
    }

    public function saveExperience()
    {
        // Validate the inputs
        $this->validate([
            'education_qualification' => 'required|numeric|min:0|max:85',
            'experience_type' => 'required|numeric|min:0|max:25',
            'licensure_examination' => 'required|numeric|min:3|max:5',
            'place_board_exam' => 'required|numeric',
            'professional_activities' => 'required|numeric|min:0|max:15',
            'academic_performance' => 'required|numeric',
            'publication' => 'required|numeric',
            'school_graduate' => 'required|numeric',
        ], [
            'education_qualification.required' => 'Please enter Educational Qualification points',
            'education_qualification.max' => 'Educational Qualification cannot exceed 85 points',
            'experience_type.required' => 'Please enter Academic/Administrative Experience points',
            'experience_type.max' => 'Academic/Administrative Experience cannot exceed 25 points',
            'licensure_examination.required' => 'Please enter Licensure Examination points',
            'licensure_examination.min' => 'Licensure Examination must be at least 3 points',
            'licensure_examination.max' => 'Licensure Examination cannot exceed 5 points',
            'place_board_exam.required' => 'Please select Place in Board Examination',
            'professional_activities.required' => 'Please enter Professional Activities points',
            'professional_activities.max' => 'Professional Activities cannot exceed 15 points',
            'academic_performance.required' => 'Please select Academic Performance',
            'publication.required' => 'Please select Publications',
            'school_graduate.required' => 'Please select School Graduated from',
        ]);

        // Calculate total before saving
        $this->calculateTotal();

        try {
            DB::beginTransaction();

            // Create the Experience record
            $experience = ModelsExperience::create([
                'education_qualification' => $this->education_qualification,
                'experience_type' => $this->experience_type,
                'licensure_examination' => $this->licensure_examination,
                'passing_licensure_examination' => 0,
                'place_board_exam' => $this->place_board_exam,
                'professional_activities' => $this->professional_activities,
                'academic_performance' => $this->academic_performance,
                'publication' => $this->publication,
                'school_graduate' => $this->school_graduate,
                'total_score' => $this->totalScore,
            ]);

            // Update the PanelAssignment
            $user = Auth::user();
            $panel = $user->panel;
            
            if (!$panel) {
                throw new \Exception('Panel not found for current user');
            }

            $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
                ->where('evaluation_id', $this->evaluationId)
                ->first();

            if (!$panelAssignment) {
                throw new \Exception('Panel assignment not found');
            }

            $panelAssignment->update([
                'status' => 'complete',
                'experience_id' => $experience->id,
            ]);

            DB::commit();

            session()->flash('message', 'Experience evaluation saved successfully.');
            $this->dispatch('evaluationSaved');

        } catch (\Exception $e) {
            DB::rollBack();
            
            session()->flash('error', 'Failed to save experience evaluation: ' . $e->getMessage());
            $this->dispatch('evaluationError');
        }
    }

    public function render()
    {
        return view('livewire.panel.experience');
    }
}