<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use App\Models\JobApplication as ModelsJobApplication;
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
    }

    public function confirmSubmission()
    {
        $this->dispatch('show-swal-confirm');
    }

    public function save()
    {
        $this->validate();

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

        $this->dispatch('swal:success', [
            'message' => 'Application successfully submitted!',
        ]);

        return $this->redirectRoute('apply-job');
    }
    public function render()
    {
        return view('livewire.applicant.job-application')
            ->layout('layouts.app');
    }
}
