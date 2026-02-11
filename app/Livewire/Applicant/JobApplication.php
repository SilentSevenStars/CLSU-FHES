<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use App\Models\JobApplication as ModelsJobApplication;
use App\Models\Position;
use App\Models\EducationalBackground;
use App\Services\FileEncryptionService;
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
    public string $suffix = "";
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
    public $educationOptions = [];
    public $experience;
    public $training;
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
        'suffix' => 'nullable|string|max:5',
        'phone_number' => 'required|regex:/^09[0-9]{9}$/|size:11',
        'region' => 'required|string|max:255',
        'province' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'barangay' => 'required|string|max:255',
        'street' => 'required|string|max:255',
        'postal_code' => 'required|string|max:10',
        'present_position' => 'required|string|max:255',
        'education' => 'required|string|max:255',
        'experience' => 'required|integer|min:0',
        'training' => 'required|integer|min:0',
        'eligibility' => 'required|string|max:255',
        'other_involvement' => 'required|string|max:255',
        'requirements_file' => 'required|mimes:pdf|max:102400', 
    ];

    protected $messages = [
        'phone_number.regex' => 'Phone number must start with 09 and contain exactly 11 digits.',
        'phone_number.size' => 'Phone number must be exactly 11 digits.',
        'requirements_file.max' => 'The file size must not exceed 100MB.',
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

            // Load applicant data including suffix
            $this->first_name = $applicant->first_name;
            $this->middle_name = $applicant->middle_name ?? '';
            $this->last_name = $applicant->last_name;
            $this->suffix = $applicant->suffix ?? '';
            $this->phone_number = $applicant->phone_number ?? '';
            $this->region = $applicant->region ?? '';
            $this->province = $applicant->province ?? '';
            $this->city = $applicant->city ?? '';
            $this->barangay = $applicant->barangay ?? '';
            $this->street = $applicant->street ?? '';
            $this->postal_code = $applicant->postal_code ?? '';
            $this->present_position = $applicant->position ?? '';
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

        $this->educationOptions = EducationalBackground::orderBy('name')
        ->pluck('name')
        ->toArray();
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

        // Map region names to display format
        $this->mapRegionNames();
    }

    private function mapRegionNames()
    {
        $regionMapping = [
            'Ilocos Region' => 'Region I',
            'Cagayan Valley' => 'Region II',
            'Central Luzon' => 'Region III',
            'CALABARZON' => 'Region IV-A',
            'MIMAROPA Region' => 'Region IV-B',
            'Bicol Region' => 'Region V',
            'Western Visayas' => 'Region VI',
            'Central Visayas' => 'Region VII',
            'Eastern Visayas' => 'Region VIII',
            'Zamboanga Peninsula' => 'Region IX',
            'Northern Mindanao' => 'Region X',
            'Davao Region' => 'Region XI',
            'SOCCSKSARGEN' => 'Region XII',
            'NCR' => 'NCR',
            'CAR' => 'CAR',
            'Caraga' => 'Region XVI',
            'BARMM' => 'BARMM',
        ];

        foreach ($this->regions as &$region) {
            $region['regionName'] = $regionMapping[$region['name']] ?? $region['name'];
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

    public function updatedPhoneNumber($value)
    {
        // Remove any non-numeric characters
        $this->phone_number = preg_replace('/[^0-9]/', '', $value);
        
        // Ensure it starts with 09
        if (strlen($this->phone_number) > 0 && !str_starts_with($this->phone_number, '09')) {
            if (str_starts_with($this->phone_number, '9')) {
                $this->phone_number = '0' . $this->phone_number;
            } else if (strlen($this->phone_number) >= 2) {
                $this->phone_number = '09' . substr($this->phone_number, 2);
            } else {
                $this->phone_number = '09';
            }
        }
        
        // Limit to 11 digits
        if (strlen($this->phone_number) > 11) {
            $this->phone_number = substr($this->phone_number, 0, 11);
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

        // Encrypt and store the file
        $encryptionService = new FileEncryptionService();
        $encryptedPath = $encryptionService->encryptAndStore($this->requirements_file);

        $applicant = Applicant::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'suffix' => $this->suffix,
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
            'requirements_file' => $encryptedPath,
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