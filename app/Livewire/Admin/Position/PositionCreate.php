<?php

namespace App\Livewire\Admin\Position;

use App\Models\College;
use App\Models\Department;
use App\Models\EducationalBackground;
use App\Models\Position;
use App\Models\PositionRank;
use App\Services\AccountActivityService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PositionCreate extends Component
{
    public string $name = "";
    public $college_id = null;
    public $department_id = null;
    public $start_date;
    public $end_date;
    public string $specialization = "";
    public string $education = "";
    public int $experience = 0;
    public int $training = 0;
    public string $eligibility = "";
    public $educationOptions = [];

    public $colleges = [];
    public $departments = [];
    public $positionRanks = [];

    protected array $positionRequirements = [
        'Instructor I'                 => ['experience' => 0, 'training' => 0,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Instructor II'                => ['experience' => 0, 'training' => 0,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Instructor III'               => ['experience' => 1, 'training' => 4,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Assistant Professor I'        => ['experience' => 1, 'training' => 4,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Assistant Professor II'       => ['experience' => 1, 'training' => 4,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Assistant Professor III'      => ['experience' => 1, 'training' => 4,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Assistant Professor IV'       => ['experience' => 2, 'training' => 8,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Associate Professor I'        => ['experience' => 2, 'training' => 8,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Associate Professor II'       => ['experience' => 2, 'training' => 8,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Associate Professor III'      => ['experience' => 2, 'training' => 8,  'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Associate Professor IV'       => ['experience' => 3, 'training' => 16, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Associate Professor V'        => ['experience' => 3, 'training' => 16, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility)'],
        'Professor I'                  => ['experience' => 4, 'training' => 24, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility) with PASUC Professional Accreditation'],
        'Professor II'                 => ['experience' => 5, 'training' => 32, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility) with PASUC Professional Accreditation'],
        'Professor III'                => ['experience' => 5, 'training' => 32, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility) with PASUC Professional Accreditation'],
        'Professor IV'                 => ['experience' => 5, 'training' => 32, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility) With Professional Accreditation'],
        'Professor V'                  => ['experience' => 5, 'training' => 32, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility) With Professional Accreditation'],
        'Professor VI'                 => ['experience' => 5, 'training' => 32, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility) With Professional Accreditation'],
        'College/University Professor' => ['experience' => 5, 'training' => 32, 'eligibility' => 'None required, RA 1080 (For courses requiring BAR or BOARD eligibility) With University Professional Accreditation'],
    ];

    public function mount()
    {
        $this->colleges       = College::orderBy('name')->get();
        $this->positionRanks  = PositionRank::orderBy('id')->get();
        $this->educationOptions = EducationalBackground::orderBy('name')->pluck('name')->toArray();
    }

    public function updatedName($value)
    {
        if (isset($this->positionRequirements[$value])) {
            $req = $this->positionRequirements[$value];
            $this->experience  = $req['experience'];
            $this->training    = $req['training'];
            $this->eligibility = $req['eligibility'];
        } else {
            $this->experience  = 0;
            $this->training    = 0;
            $this->eligibility = '';
        }
    }

    public function updatedCollegeId($value)
    {
        $this->department_id = null;
        $this->departments   = $value
            ? Department::where('college_id', $value)->orderBy('name')->get()
            : [];
    }

    public function store()
    {
        $this->validate([
            'name'           => 'required|string|max:255',
            'college_id'     => 'nullable|exists:colleges,id',
            'department_id'  => 'nullable|exists:departments,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'specialization' => 'required|string|max:255',
            'education'      => 'required|string|max:255',
            'experience'     => 'required|integer|min:0',
            'training'       => 'required|integer|min:0',
            'eligibility'    => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $position = new Position();
            $position->name           = $this->name;
            $position->college_id     = $this->college_id    ?: null;
            $position->department_id  = $this->department_id ?: null;
            $position->start_date     = $this->start_date;
            $position->end_date       = $this->end_date;
            $position->specialization = $this->specialization;
            $position->education      = $this->education;
            $position->experience     = $this->experience;
            $position->training       = $this->training;
            $position->eligibility    = $this->eligibility;
            $position->save();

            DB::commit();

            // ── Activity log ─────────────────────────────────────────────────
            $collegeName    = $position->college->name   ?? 'Various Colleges';
            $departmentName = $position->department->name ?? 'Various Departments';

            AccountActivityService::log(
                Auth::user(),
                "Created a new position \"{$this->name}\" — College: {$collegeName}, Department: {$departmentName}, Specialization: {$this->specialization}, Education: {$this->education}, Experience: {$this->experience} year(s), Training: {$this->training} hour(s), Start: {$this->start_date}, End: {$this->end_date}."
            );
            // ─────────────────────────────────────────────────────────────────

            session()->flash('success', 'Position has been created successfully');
            return redirect()->route('admin.position');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create position: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.position');
    }

    public function render()
    {
        return view('livewire.admin.position.position-create');
    }
}