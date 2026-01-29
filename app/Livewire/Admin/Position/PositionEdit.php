<?php

namespace App\Livewire\Admin\Position;

use App\Models\College;
use App\Models\Department;
use App\Models\Position;
use App\Models\PositionRank;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PositionEdit extends Component
{
    public $position_id;
    public string $name = "";
    public $college_id = "";        // Changed from 'college' to 'college_id'
    public $department_id = "";     // Changed from 'department' to 'department_id'
    public string $status = "";
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

    public function mount($id)
    {
        $this->colleges = College::orderBy('name')->get();
        $this->positionRanks = PositionRank::orderBy('id')->get();

        // Load position with relationships
        $position = Position::with(['college', 'department'])->findOrFail($id);

        $this->position_id = $position->id;
        $this->name = $position->name;
        $this->status = $position->status;
        $this->start_date = $position->start_date;
        $this->end_date = $position->end_date;
        $this->specialization = $position->specialization;
        $this->education = $position->education;
        $this->experience = $position->experience;
        $this->training = $position->training;
        $this->eligibility = $position->eligibility;

        // IMPORTANT: Set college_id first, load departments, then set department_id
        $this->college_id = (string) $position->college_id;
        
        // Load departments for the selected college
        if ($this->college_id) {
            $this->departments = Department::where('college_id', $this->college_id)
                ->orderBy('name')
                ->get();
        }
        
        // Set department_id after departments are loaded
        $this->department_id = (string) $position->department_id;
    }

    /**
     * When college is selected, load its departments
     * Method name changed from updatedCollege to updatedCollegeId
     */
    public function updatedCollegeId($value)
    {
        if ($value) {
            // Use college_id foreign key instead of college name
            $this->departments = Department::where('college_id', $value)
                ->orderBy('name')
                ->get();
        } else {
            $this->departments = [];
            $this->department_id = "";
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'college_id' => 'required|exists:colleges,id',        // Updated validation
            'department_id' => 'required|exists:departments,id',  // Updated validation
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
            $position = Position::findOrFail($this->position_id);
            $position->name = $this->name;
            $position->college_id = $this->college_id;         // Use foreign key
            $position->department_id = $this->department_id;   // Use foreign key
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