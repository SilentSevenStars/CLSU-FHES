<?php

namespace App\Livewire\Admin\Position;

use App\Models\College;
use App\Models\Department;
use App\Models\Position;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PositionEdit extends Component
{
    public $position_id;
    public string $name = "";
    public string $college = "";
    public string $department = "";
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

    public function mount($id)
    {
        $this->colleges = College::orderBy('name')->get();
        
        $position = Position::findOrFail($id);
        $this->position_id = $position->id;
        $this->name = $position->name;
        $this->college = trim($position->college);
        $this->department = $position->department;
        $this->status = $position->status;
        $this->start_date = $position->start_date;
        $this->end_date = $position->end_date;
        $this->specialization = $position->specialization;
        $this->education = $position->education;
        $this->experience = $position->experience;
        $this->training = $position->training;
        $this->eligibility = $position->eligibility;

        $this->departments = Department::where('college', $this->college)->orderBy('name')->get();
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

    public function update()
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
            $position = Position::findOrFail($this->position_id);
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
        return view('livewire.admin.position.position-edit')->layout('layouts.app');
    }
}
