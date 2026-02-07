<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileView extends Component
{
    public $applicant;
    public $fullName;
    public $fullAddress;

    public function mount()
    {

        $this->applicant = Applicant::where('user_id', Auth::id())->first();
        
        if ($this->applicant) {
            $this->fullName = $this->buildFullName();
            $this->fullAddress = $this->buildFullAddress();
        }
    }

    private function buildFullName()
    {
        $name = $this->applicant->first_name;
        
        if (!empty($this->applicant->middle_name)) {
            $middleInitial = strtoupper(substr($this->applicant->middle_name, 0, 1)) . '.';
            $name .= ' ' . $middleInitial;
        }
        
        $name .= ' ' . $this->applicant->last_name;
        
        if (!empty($this->applicant->suffix)) {
            $name .= ' ' . $this->applicant->suffix;
        }
        
        return $name;
    }

    private function buildFullAddress()
    {
        $addressParts = array_filter([
            $this->applicant->street,
            $this->applicant->barangay,
            $this->applicant->city,
            $this->applicant->province,
            $this->applicant->region,
            $this->applicant->postal_code
        ]);
        
        return implode(', ', $addressParts);
    }

    public function render()
    {
        return view('livewire.applicant.profile-view');
    }
}
