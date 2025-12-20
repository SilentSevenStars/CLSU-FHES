<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use App\Models\JobApplication as ModelsJobApplication;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditJobApplication extends Component
{
    use WithFileUploads;

    public string $first_name = "";
    public string $middle_name = "";
    public string $last_name = "";
    public string $phone_number = "";
    public string $address = "";
    public string $present_position = "";
    public string $education = "";
    public $experience;
    public string $training = "";
    public string $eligibility = "";
    public string $other_involvement = "";
    public $requirements_file;
    public $application_id;
    public $position_id;
    public $existing_file_path = null;

    public $deadlineTimestamp; 
    public $isSubmitting = false; 

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:13',
        'address' => 'required|string|max:255',
        'present_position' => 'required|string|max:255',
        'education' => 'required|string|max:255',
        'experience' => 'required|integer|min:0',
        'training' => 'required|string|max:255',
        'eligibility' => 'required|string|max:255',
        'other_involvement' => 'required|string|max:255',
        'requirements_file' => 'nullable|mimes:pdf|max:2048',
    ];

    public function mount($application_id)
    {
        $this->application_id = $application_id;

        $applicant = Applicant::where('user_id', Auth::id())->first();

        if (!$applicant) {
            session()->flash('error', 'Applicant profile not found.');
            return redirect()->route('apply-job');
        }

        // Load the application
        $application = ModelsJobApplication::where('id', $application_id)
            ->where('applicant_id', $applicant->id)
            ->first();

        if (!$application) {
            session()->flash('error', 'Application not found or you do not have permission to edit it.');
            return redirect()->route('apply-job');
        }

        $this->position_id = $application->position_id;
        $position = Position::find($this->position_id);

        // Check if position exists and is within date range
        if (!$position) {
            session()->flash('error', 'Position not found.');
            return redirect()->route('apply-job');
        }

        $today = Carbon::today();
        $isWithinDateRange = $today->between(
            Carbon::parse($position->start_date), 
            Carbon::parse($position->end_date)
        );

        if (!$isWithinDateRange) {
            session()->flash('error', 'This position is no longer accepting edits. The application deadline has passed.');
            return redirect()->route('apply-job');
        }

        // Load applicant personal info
        $this->first_name = $applicant->first_name;
        $this->middle_name = $applicant->middle_name;
        $this->last_name = $applicant->last_name;
        $this->phone_number = $applicant->phone_number ?? '';
        $this->address = $applicant->address ?? '';

        // Load application data
        $this->present_position = $application->present_position;
        $this->education = $application->education;
        $this->experience = $application->experience;
        $this->training = $application->training;
        $this->eligibility = $application->eligibility;
        $this->other_involvement = $application->other_involvement;
        $this->existing_file_path = $application->requirements_file;

        // Set deadline
        $deadline = Carbon::parse($position->end_date)->addDay()->startOfDay();
        $this->deadlineTimestamp = $deadline->timestamp;
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function confirmSubmission()
    {
        $this->dispatch('show-swal-confirm');
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('scroll-to-error');
            throw $e;
        }

        $this->isSubmitting = true;

        // Handle file upload
        $pdfPath = $this->existing_file_path;
        if ($this->requirements_file) {
            // Delete old file
            if ($this->existing_file_path) {
                Storage::disk('public')->delete($this->existing_file_path);
            }
            $pdfPath = $this->requirements_file->store('requirements', 'public');
        }

        // Update applicant
        $applicant = Applicant::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
            ]
        );

        // Update application
        ModelsJobApplication::where('id', $this->application_id)->update([
            'present_position' => $this->present_position,
            'education' => $this->education,
            'experience' => $this->experience,
            'training' => $this->training,
            'eligibility' => $this->eligibility,
            'other_involvement' => $this->other_involvement,
            'requirements_file' => $pdfPath,
        ]);

        // Dispatch event to notify other components
        $this->dispatch('job-application-submitted');

        return redirect()->route('apply-job')->with('success', 'Application successfully updated!');
    }

    public function render()
    {
        return view('livewire.applicant.edit-job-application');
    }
}