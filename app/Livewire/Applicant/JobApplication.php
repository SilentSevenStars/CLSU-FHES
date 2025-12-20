<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use App\Models\JobApplication as ModelsJobApplication;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobApplication extends Component
{
    use WithFileUploads;

    public string $first_name = "";
    public string $middle_name = "";
    public string $last_name = "";
    public string $phone_number = "";

    // New address fields
    public string $region = "";
    public string $province = "";
    public string $city = "";
    public string $barangay = "";
    public string $street = "";
    public string $postal_code = "";

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

    // For dropdown data
    public $regions = [];
    public $provinces = [];
    public $cities = [];
    public $barangays = [];

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:13',
        'region' => 'required|string|max:255',
        'province' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'barangay' => 'required|string|max:255',
        'street' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:10',
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

        $position = Position::find($position_id);

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
            session()->flash('error', 'This position is no longer accepting applications.');
            return redirect()->route('apply-job');
        }

        $applicant = Applicant::where('user_id', Auth::id())->first();
        if ($applicant) {
            $existingApplication = ModelsJobApplication::where('applicant_id', $applicant->id)
                ->where('position_id', $position_id)
                ->first();

            if ($existingApplication) {
                session()->flash('error', 'You have already applied for this position.');
                return redirect()->route('apply-job');
            }

            // Load applicant data including new address fields
            $this->first_name = $applicant->first_name;
            $this->middle_name = $applicant->middle_name;
            $this->last_name = $applicant->last_name;
            $this->phone_number = $applicant->phone_number ?? '';
            $this->region = $applicant->region ?? '';
            $this->province = $applicant->province ?? '';
            $this->city = $applicant->city ?? '';
            $this->barangay = $applicant->barangay ?? '';
            $this->street = $applicant->street ?? '';
            $this->postal_code = $applicant->postal_code ?? '';
        }

        // Load initial regions
        $this->loadRegions();

        // If editing, load dependent data
        if ($this->region) {
            $this->loadProvinces();
        }
        if ($this->province) {
            $this->loadCities();
        }
        if ($this->city) {
            $this->loadBarangays();
        }

        $deadline = Carbon::parse($position->end_date)->addDay()->startOfDay();
        $this->deadlineTimestamp = $deadline->timestamp;
    }

    public function loadRegions()
    {
        // Fallback static data for Philippine regions
        $this->regions = [
            ['code' => '010000000', 'name' => 'Ilocos Region'],
            ['code' => '020000000', 'name' => 'Cagayan Valley'],
            ['code' => '030000000', 'name' => 'Central Luzon'],
            ['code' => '040000000', 'name' => 'CALABARZON'],
            ['code' => '170000000', 'name' => 'MIMAROPA Region'],
            ['code' => '050000000', 'name' => 'Bicol Region'],
            ['code' => '060000000', 'name' => 'Western Visayas'],
            ['code' => '070000000', 'name' => 'Central Visayas'],
            ['code' => '080000000', 'name' => 'Eastern Visayas'],
            ['code' => '090000000', 'name' => 'Zamboanga Peninsula'],
            ['code' => '100000000', 'name' => 'Northern Mindanao'],
            ['code' => '110000000', 'name' => 'Davao Region'],
            ['code' => '120000000', 'name' => 'SOCCSKSARGEN'],
            ['code' => '130000000', 'name' => 'NCR'],
            ['code' => '140000000', 'name' => 'CAR'],
            ['code' => '160000000', 'name' => 'Caraga'],
            ['code' => '190000000', 'name' => 'BARMM'],
        ];

        try {
            $response = Http::withOptions(['verify' => false])->get('https://psgc.gitlab.io/api/regions/');
            if ($response->successful()) {
                $this->regions = $response->json();
            }
        } catch (\Exception $e) {
            // Use fallback data
        }
    }

    public function loadProvinces()
    {
        if (!$this->region) return;

        try {
            $selectedRegion = collect($this->regions)
                ->firstWhere('name', $this->region);

            if (!$selectedRegion) return;

            $response = Http::withOptions(['verify' => false])->get(
                "https://psgc.gitlab.io/api/regions/{$selectedRegion['code']}/provinces/"
            );

            if ($response->successful()) {
                $this->provinces = $response->json();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load provinces.');
        }
    }


    public function loadCities()
    {
        if (!$this->province) return;

        try {
            $selectedProvince = collect($this->provinces)
                ->firstWhere('name', $this->province);

            if (!$selectedProvince) return;

            $response = Http::withOptions(['verify' => false])->get(
                "https://psgc.gitlab.io/api/provinces/{$selectedProvince['code']}/cities-municipalities/"
            );

            if ($response->successful()) {
                $this->cities = $response->json();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load cities.');
        }
    }


    public function loadBarangays()
    {
        if (!$this->city) return;

        try {
            $selectedCity = collect($this->cities)
                ->firstWhere('name', $this->city);

            if (!$selectedCity) return;

            $response = Http::withOptions(['verify' => false])->get(
                "https://psgc.gitlab.io/api/cities-municipalities/{$selectedCity['code']}/barangays/"
            );

            if ($response->successful()) {
                $this->barangays = $response->json();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load barangays.');
        }
    }


    public function updatedRegion($value)
    {
        $this->province = '';
        $this->city = '';
        $this->barangay = '';

        $this->provinces = [];
        $this->cities = [];
        $this->barangays = [];

        if ($value) {
            $this->loadProvinces();
        }
    }

    public function updatedProvince($value)
    {
        $this->city = '';
        $this->barangay = '';

        $this->cities = [];
        $this->barangays = [];

        if ($value) {
            $this->loadCities();
        }
    }

    public function updatedCity($value)
    {
        $this->barangay = '';
        $this->barangays = [];

        if ($value) {
            $this->loadBarangays();
        }
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
                'region' => $this->region,
                'province' => $this->province,
                'city' => $this->city,
                'barangay' => $this->barangay,
                'street' => $this->street,
                'postal_code' => $this->postal_code,
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

        $this->dispatch('job-application-submitted');

        return redirect()->route('apply-job')
            ->with('success', 'Application successfully submitted! Please wait for the admin to review your application.');
    }

    public function render()
    {
        return view('livewire.applicant.job-application');
    }
}
