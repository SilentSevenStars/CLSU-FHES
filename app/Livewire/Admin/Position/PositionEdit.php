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

class PositionEdit extends Component
{
    public $position_id;
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

    // Snapshot of original DB values for diffing on save.
    // Must be PUBLIC so Livewire serialises them into the component state
    // and preserves them across subsequent wire requests (e.g. updatedName,
    // updatedCollegeId, etc.) — private/protected properties are NOT kept.
    public string $oldName           = "";
    public string $oldCollegeName    = "";
    public string $oldDepartmentName = "";
    public string $oldSpecialization = "";
    public string $oldEducation      = "";
    public int    $oldExperience     = 0;
    public int    $oldTraining       = 0;
    public string $oldStartDate      = "";
    public string $oldEndDate        = "";

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

    public function mount($id)
    {
        $this->colleges      = College::orderBy('name')->get();
        $this->positionRanks = PositionRank::orderBy('id')->get();

        $position = Position::with(['college', 'department'])->findOrFail($id);

        $this->position_id    = $position->id;
        $this->name           = $position->name;
        $this->start_date     = $position->start_date;
        $this->end_date       = $position->end_date;
        $this->specialization = $position->specialization;
        $this->education      = $position->education;
        $this->experience     = $position->experience;
        $this->training       = $position->training;
        $this->eligibility    = $position->eligibility;
        $this->college_id     = $position->college_id    ? (string) $position->college_id    : null;
        $this->department_id  = $position->department_id ? (string) $position->department_id : null;

        if ($this->college_id) {
            $this->departments = Department::where('college_id', $this->college_id)->orderBy('name')->get();
        }

        $this->educationOptions = EducationalBackground::orderBy('name')->pluck('name')->toArray();

        // Store originals — set once in mount, never touched again until update()
        $this->oldName           = $position->name;
        $this->oldCollegeName    = $position->college->name    ?? 'Various Colleges';
        $this->oldDepartmentName = $position->department->name ?? 'Various Departments';
        $this->oldSpecialization = $position->specialization;
        $this->oldEducation      = $position->education;
        $this->oldExperience     = $position->experience;
        $this->oldTraining       = $position->training;
        $this->oldStartDate      = $position->start_date ?? '';
        $this->oldEndDate        = $position->end_date   ?? '';
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

    public function update()
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
            $position = Position::findOrFail($this->position_id);
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

            // ── Activity log — only record what actually changed ──────────────
            $position->load(['college', 'department']);
            $newCollegeName    = $position->college->name    ?? 'Various Colleges';
            $newDepartmentName = $position->department->name ?? 'Various Departments';

            $changes = [];

            if ($this->oldName !== $this->name)
                $changes[] = "name: \"{$this->oldName}\" → \"{$this->name}\"";

            if ($this->oldCollegeName !== $newCollegeName)
                $changes[] = "college: \"{$this->oldCollegeName}\" → \"{$newCollegeName}\"";

            if ($this->oldDepartmentName !== $newDepartmentName)
                $changes[] = "department: \"{$this->oldDepartmentName}\" → \"{$newDepartmentName}\"";

            if ($this->oldSpecialization !== $this->specialization)
                $changes[] = "specialization: \"{$this->oldSpecialization}\" → \"{$this->specialization}\"";

            if ($this->oldEducation !== $this->education)
                $changes[] = "education: \"{$this->oldEducation}\" → \"{$this->education}\"";

            if ($this->oldExperience !== $this->experience)
                $changes[] = "experience: {$this->oldExperience} → {$this->experience} year(s)";

            if ($this->oldTraining !== $this->training)
                $changes[] = "training: {$this->oldTraining} → {$this->training} hour(s)";

            if ($this->oldStartDate !== $this->start_date)
                $changes[] = "start date: {$this->oldStartDate} → {$this->start_date}";

            if ($this->oldEndDate !== $this->end_date)
                $changes[] = "end date: {$this->oldEndDate} → {$this->end_date}";

            // Skip writing a log entry entirely if nothing changed
            if (!empty($changes)) {
                AccountActivityService::log(
                    Auth::user(),
                    "Updated position \"{$this->name}\" (ID: {$this->position_id}) — "
                        . implode('; ', $changes) . '.'
                );
            }
            // ─────────────────────────────────────────────────────────────────

            session()->flash('success', 'Position has been updated successfully');
            return redirect()->route('admin.position');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update position: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.position');
    }

    public function render()
    {
        return view('livewire.admin.position.position-edit');
    }
}