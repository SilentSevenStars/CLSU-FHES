<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use App\Models\JobApplication as ModelsJobApplication;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobApplication extends Component
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
    public $position_id;

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
        'requirements_file' => 'required|mimes:pdf|max:2048',
    ];

    public function mount($position_id)
    {
        $this->position_id = $position_id;

        $applicant = Applicant::where('user_id', Auth::id())->first();
        if ($applicant) {
            $this->first_name = $applicant->first_name;
            $this->middle_name = $applicant->middle_name;
            $this->last_name = $applicant->last_name;
            $this->phone_number = $applicant->phone_number ?? '';
            $this->address = $applicant->address ?? '';
        }

        $position = Position::find($position_id);

        if ($position) {
            $deadline = Carbon::parse($position->end_date)->addDay()->startOfDay();
            $this->deadlineTimestamp = $deadline->timestamp;
        }
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

        $pdfPath = $this->requirements_file->store('requirements', 'public');

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

        ModelsJobApplication::create([
            'present_position' => $this->present_position,
            'education' => $this->education,
            'experience' => $this->experience,
            'training' => $this->training,
            'eligibility' => $this->eligibility,
            'other_involvement' => $this->other_involvement,
            'requirements_file' => $pdfPath,
            'applicant_id' => $applicant->id,
            'position_id' => $this->position_id,
        ]);

        // Dispatch event to notify other components
        $this->dispatch('job-application-submitted');

        return redirect()->route('apply-job')
            ->with('success', 'Application successfully submitted! Please wait for the admin to review your application.');
    }

    public function render()
    {
        return view('livewire.applicant.job-application');
    }
}
