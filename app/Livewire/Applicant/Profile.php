<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Profile extends Component
{
    public string $first_name = "";
    public string $middle_name = "";
    public string $last_name = "";
    public string $suffix = "";
    public string $phone_number = "";
    public string $region = "";
    public string $province = "";
    public string $city = "";
    public string $barangay = "";
    public string $street = "";
    public string $postal_code = "";

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
        'street' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:10',
    ];

    protected $messages = [
        'phone_number.regex' => 'Phone number must start with 09 and contain exactly 11 digits.',
        'phone_number.size' => 'Phone number must be exactly 11 digits.',
    ];

    public function mount()
    {

        $applicant = Applicant::where('user_id', Auth::id())->first();
        
        if ($applicant) {
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
        }

        $this->loadRegions();

        if ($this->region) {
            $this->loadProvinces();
        }
        if ($this->province) {
            $this->loadCities();
        }
        if ($this->city) {
            $this->loadBarangays();
        }
    }

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
            // Use fallback data
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
            $selectedProvince = collect($this->provinces)->firstWhere('name', $this->province);
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
            $selectedCity = collect($this->cities)->firstWhere('name', $this->city);
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
        $this->phone_number = preg_replace('/[^0-9]/', '', $value);
        
        if (strlen($this->phone_number) > 0 && !str_starts_with($this->phone_number, '09')) {
            if (str_starts_with($this->phone_number, '9')) {
                $this->phone_number = '0' . $this->phone_number;
            } else if (strlen($this->phone_number) >= 2) {
                $this->phone_number = '09' . substr($this->phone_number, 2);
            } else {
                $this->phone_number = '09';
            }
        }
        
        if (strlen($this->phone_number) > 11) {
            $this->phone_number = substr($this->phone_number, 0, 11);
        }
    }

    public function updateProfile()
    {
        $this->validate();

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

        // Update user name
        $fullName = $this->buildFullName();
        User::where('id', Auth::id())->update(['name' => $fullName]);

        session()->flash('success', 'Profile updated successfully!');
        $this->dispatch('profile-updated');
    }

    private function buildFullName()
    {
        $name = $this->first_name;
        
        if (!empty($this->middle_name)) {
            $middleInitial = strtoupper(substr($this->middle_name, 0, 1)) . '.';
            $name .= ' ' . $middleInitial;
        }
        
        $name .= ' ' . $this->last_name;
        
        if (!empty($this->suffix)) {
            $name .= ' ' . $this->suffix;
        }
        
        return $name;
    }

    public function render()
    {
        return view('livewire.applicant.profile');
    }
}
