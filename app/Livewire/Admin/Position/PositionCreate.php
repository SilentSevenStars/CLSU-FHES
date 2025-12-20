<?php

namespace App\Livewire\Admin\Position;

use App\Models\College;
use App\Models\Department;
use App\Models\Position;
use App\Models\PositionRank;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PositionCreate extends Component
{
    public string $name = "";
    public string $college = "";
    public string $department = "";
    public string $status = "vacant";
    public $start_date;
    public $end_date;
    public string $specialization = "";
    public string $education = "";
    public int $experience = 0;
    public int $training = 0;
    public string $eligibility = "";

    public $colleges = [];
    public $departments = [];
    public $positionRanks = [];

    public function mount()
    {
        $this->colleges = College::orderBy('name')->get();
        $this->positionRanks = PositionRank::orderBy('id')->get();
    }

    public function updatedCollege($value)
    {
        if ($value) {
            $this->departments = Department::where('college', $value)->orderBy('name')->get();
        } else {
            $this->departments = [];
            $this->department = "";
        }
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'college' => 'required|string',
            'department' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'specialization' => 'required|string|max:255',
            'education' => 'required|string|max:255',
            'experience' => 'required|integer|min:0',
            'training' => 'required|integer|min:0',
            'eligibility' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $position = new Position();
            $position->name = $this->name;
            $position->college = $this->college;
            $position->department = $this->department;
            $position->status = $this->status;
            $position->start_date = $this->start_date;
            $position->end_date = $this->end_date;
            $position->specialization = $this->specialization;
            $position->education = $this->education;
            $position->experience = $this->experience;
            $position->training = $this->training;
            $position->eligibility = $this->eligibility;
            $position->save();

            DB::commit();

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
