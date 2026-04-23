<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use App\Models\JobApplication as ModelsJobApplication;
use App\Models\Position;
use App\Models\EducationalBackground;
use App\Services\AccountActivityService;
use App\Services\FileEncryptionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobApplication extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;
    public int $totalSteps  = 5;

    public string $first_name  = "";
    public string $middle_name = "";
    public string $last_name   = "";
    public string $suffix      = "";
    public string $phone_number = "";

    public string $region      = "";
    public string $province    = "";
    public string $city        = "";
    public string $barangay    = "";
    public string $street      = "";
    public string $postal_code = "";

    public string $present_position  = "";
    public string $education         = "";
    public $educationOptions         = [];
    public $experience;
    public $training;
    public string $eligibility       = "";
    public bool   $eligibilityIsFixed = false;
    public string $positionEligibility = "";
    public string $other_involvement  = "";

    public $requirements_file;
    public $position_id;
    public bool $agree_to_terms = false;

    public $deadlineTimestamp;
    public $isSubmitting = false;

    public $regions   = [];
    public $provinces = [];
    public $cities    = [];
    public $barangays = [];

    private const INACTIVE_STATUSES = ['hired', 'decline'];

    protected array $stepRules = [
        1 => [
            'agree_to_terms' => 'accepted',
        ],
        2 => [
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'suffix'       => 'nullable|string|max:5',
            'phone_number' => 'required|regex:/^09[0-9]{9}$/|size:11',
        ],
        3 => [
            'region'      => 'required|string|max:255',
            'province'    => 'required|string|max:255',
            'city'        => 'required|string|max:255',
            'barangay'    => 'required|string|max:255',
            'street'      => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ],
        4 => [
            'present_position'  => 'nullable|string|max:255',
            'education'         => 'required|string|max:255',
            'experience'        => 'required|integer|min:0',
            'training'          => 'required|integer|min:0',
            'eligibility'       => 'required|string|max:255',
            'other_involvement' => 'nullable|string|max:255',
        ],
        5 => [
            'requirements_file' => 'required|mimes:pdf|max:102400',
        ],
    ];

    protected $rules = [
        'first_name'        => 'required|string|max:255',
        'middle_name'       => 'nullable|string|max:255',
        'last_name'         => 'required|string|max:255',
        'suffix'            => 'nullable|string|max:5',
        'phone_number'      => 'required|regex:/^09[0-9]{9}$/|size:11',
        'region'            => 'required|string|max:255',
        'province'          => 'required|string|max:255',
        'city'              => 'required|string|max:255',
        'barangay'          => 'required|string|max:255',
        'street'            => 'required|string|max:255',
        'postal_code'       => 'required|string|max:10',
        'present_position'  => 'nullable|string|max:255',
        'education'         => 'required|string|max:255',
        'experience'        => 'required|integer|min:0',
        'training'          => 'required|integer|min:0',
        'eligibility'       => 'required|string|max:255',
        'other_involvement' => 'nullable|string|max:255',
        'requirements_file' => 'required|mimes:pdf|max:102400',
        'agree_to_terms'    => 'accepted',
    ];

    protected $messages = [
        'phone_number.regex'      => 'Phone number must start with 09 and contain exactly 11 digits.',
        'phone_number.size'       => 'Phone number must be exactly 11 digits.',
        'requirements_file.max'   => 'The file size must not exceed 100MB.',
        'agree_to_terms.accepted' => 'You must agree to the Data Privacy Act terms before proceeding.',
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
                ->where('archive', false)
                ->whereNotIn('status', self::INACTIVE_STATUSES)
                ->first();

            if ($existingApplication) {
                session()->flash('error', 'You have already applied for this position.');
                return redirect()->route('apply-job');
            }

            $anyActiveApplication = ModelsJobApplication::where('applicant_id', $applicant->id)
                ->where('archive', false)
                ->whereNotIn('status', self::INACTIVE_STATUSES)
                ->exists();

            if ($anyActiveApplication) {
                session()->flash('error', 'You already have an active application. You can only apply to one position at a time.');
                return redirect()->route('apply-job');
            }

            $this->first_name       = $applicant->first_name;
            $this->middle_name      = $applicant->middle_name ?? '';
            $this->last_name        = $applicant->last_name;
            $this->suffix           = $applicant->suffix ?? '';
            $this->phone_number     = $applicant->phone_number ?? '';
            $this->region           = $applicant->region ?? '';
            $this->province         = $applicant->province ?? '';
            $this->city             = $applicant->city ?? '';
            $this->barangay         = $applicant->barangay ?? '';
            $this->street           = $applicant->street ?? '';
            $this->postal_code      = $applicant->postal_code ?? '';
            $this->present_position = $applicant->position ?? '';
        }

        $this->positionEligibility = $position->eligibility ?? '';
        if ($this->isNoneRequiredEligibility($this->positionEligibility)) {
            $this->eligibility        = 'None Required';
            $this->eligibilityIsFixed = true;
        }

        $this->loadRegions();

        if ($this->region)   $this->loadProvinces();
        if ($this->province) $this->loadCities();
        if ($this->city)     $this->loadBarangays();

        $deadline = Carbon::parse($position->end_date)->addDay()->startOfDay();
        $this->deadlineTimestamp = $deadline->timestamp;

        $this->educationOptions = EducationalBackground::orderBy('name')->pluck('name')->toArray();
    }

    private function isNoneRequiredEligibility(string $eligibility): bool
    {
        return stripos(trim($eligibility), 'None required') === 0;
    }

    // ── Navigation ────────────────────────────────────────────────────────────

    public function nextStep()
    {
        $this->validateStep($this->currentStep);

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->dispatch('step-changed');
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('step-changed');
        }
    }

    public function goToStep(int $step)
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
            $this->dispatch('step-changed');
        }
    }

    protected function validateStep(int $step): void
    {
        if ($step === 4 && $this->eligibilityIsFixed) {
            $this->eligibility = 'None Required';
        }

        $rules = $this->stepRules[$step] ?? [];

        try {
            $this->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('scroll-to-error');
            throw $e;
        }
    }

    // ── Address loaders ───────────────────────────────────────────────────────

    public function loadRegions()
    {
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
            // Use fallback
        }

        $this->mapRegionNames();
    }

    private function mapRegionNames()
    {
        $regionMapping = [
            'Ilocos Region'       => 'Region I',
            'Cagayan Valley'      => 'Region II',
            'Central Luzon'       => 'Region III',
            'CALABARZON'          => 'Region IV-A',
            'MIMAROPA Region'     => 'Region IV-B',
            'Bicol Region'        => 'Region V',
            'Western Visayas'     => 'Region VI',
            'Central Visayas'     => 'Region VII',
            'Eastern Visayas'     => 'Region VIII',
            'Zamboanga Peninsula' => 'Region IX',
            'Northern Mindanao'   => 'Region X',
            'Davao Region'        => 'Region XI',
            'SOCCSKSARGEN'        => 'Region XII',
            'NCR'                 => 'NCR',
            'CAR'                 => 'CAR',
            'Caraga'              => 'Region XVI',
            'BARMM'               => 'BARMM',
        ];

        foreach ($this->regions as &$region) {
            $region['regionName'] = $regionMapping[$region['name']] ?? $region['name'];
        }
    }

    public function loadProvinces()
    {
        if (!$this->region) return;
        try {
            $selectedRegion = collect($this->regions)->firstWhere('name', $this->region);
            if (!$selectedRegion) return;
            $response = Http::withOptions(['verify' => false])->get(
                "https://psgc.gitlab.io/api/regions/{$selectedRegion['code']}/provinces/"
            );
            if ($response->successful()) $this->provinces = $response->json();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load provinces.');
        }
    }

    public function loadCities()
    {
        if (!$this->province) return;
        try {
            $selectedProvince = collect($this->provinces)->firstWhere('name', $this->province);
            if (!$selectedProvince) return;
            $response = Http::withOptions(['verify' => false])->get(
                "https://psgc.gitlab.io/api/provinces/{$selectedProvince['code']}/cities-municipalities/"
            );
            if ($response->successful()) $this->cities = $response->json();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load cities.');
        }
    }

    public function loadBarangays()
    {
        if (!$this->city) return;
        try {
            $selectedCity = collect($this->cities)->firstWhere('name', $this->city);
            if (!$selectedCity) return;
            $response = Http::withOptions(['verify' => false])->get(
                "https://psgc.gitlab.io/api/cities-municipalities/{$selectedCity['code']}/barangays/"
            );
            if ($response->successful()) $this->barangays = $response->json();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load barangays.');
        }
    }

    public function updatedRegion($value)
    {
        $this->province  = '';
        $this->city      = '';
        $this->barangay  = '';
        $this->provinces = [];
        $this->cities    = [];
        $this->barangays = [];
        if ($value) $this->loadProvinces();
    }

    public function updatedProvince($value)
    {
        $this->city      = '';
        $this->barangay  = '';
        $this->cities    = [];
        $this->barangays = [];
        if ($value) $this->loadCities();
    }

    public function updatedCity($value)
    {
        $this->barangay  = '';
        $this->barangays = [];
        if ($value) $this->loadBarangays();
    }

    public function updatedPhoneNumber($value)
    {
        $this->phone_number = preg_replace('/[^0-9]/', '', $value);
        if (strlen($this->phone_number) > 0 && !str_starts_with($this->phone_number, '09')) {
            if (str_starts_with($this->phone_number, '9')) {
                $this->phone_number = '0' . $this->phone_number;
            } elseif (strlen($this->phone_number) >= 2) {
                $this->phone_number = '09' . substr($this->phone_number, 2);
            } else {
                $this->phone_number = '09';
            }
        }
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
        \Illuminate\Support\Facades\Log::info('SAVE: method called');

        if ($this->eligibilityIsFixed) {
            $this->eligibility = 'None Required';
        }

        $applicant = Applicant::where('user_id', Auth::id())->first();
        if ($applicant) {
            $anyActiveApplication = ModelsJobApplication::where('applicant_id', $applicant->id)
                ->where('archive', false)
                ->whereNotIn('status', self::INACTIVE_STATUSES)
                ->exists();

            if ($anyActiveApplication) {
                \Illuminate\Support\Facades\Log::info('SAVE: blocked - active application exists');
                session()->flash('error', 'You already have an active application.');
                return redirect()->route('apply-job');
            }
        }

        try {
            $this->validate();
            \Illuminate\Support\Facades\Log::info('SAVE: validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::info('SAVE: validation failed', $e->errors());
            $this->dispatch('scroll-to-error');
            throw $e;
        }

        \Illuminate\Support\Facades\Log::info('SAVE: starting file encryption');

        try {
            $encryptionService = new FileEncryptionService();
            $encryptedPath = $encryptionService->encryptAndStore($this->requirements_file);
            \Illuminate\Support\Facades\Log::info('SAVE: file encrypted', ['path' => $encryptedPath]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SAVE: encryption failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        \Illuminate\Support\Facades\Log::info('SAVE: saving applicant and job application');

        // ... rest of your save method unchanged
    }

    public function render()
    {
        return view('livewire.applicant.job-application');
    }
}
