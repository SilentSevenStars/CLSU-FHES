<?php

namespace App\Livewire\Admin;

use App\Models\Evaluation;
use App\Models\JobApplication;
use App\Models\Position;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduledApplicant extends Component
{
    use WithPagination;

    public $selectedPositionName = '';
    public $selectedDate = '';

    public function updatingSelectedPositionName()
    {
        $this->selectedDate = '';
        $this->resetPage();
    }

    public function updatingSelectedDate()
    {
        $this->resetPage();
    }

    public function print()
    {
        if (empty($this->selectedPositionName)) {
            session()->flash('error', 'Please select a position first.');
            return;
        }

        $positionIds = Position::where('name', $this->selectedPositionName)->pluck('id');

        $query = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->whereIn('position_id', $positionIds)
            ->whereHas('evaluation');

        if ($this->selectedDate) {
            $query->whereHas('evaluation', function ($q) {
                $q->where('interview_date', $this->selectedDate);
            });
        }

        $applicants = $query->get()->map(function ($app) {
            $a = $app->applicant;  // Applicant model
            $p = $app->position;   // Position model
            $u = $a->user;         // User model

            // Build full address from applicant
            $addressParts = array_filter([
                $a->street   ?? null,
                $a->barangay ?? null,
                $a->city     ?? null,
                $a->province ?? null,
            ]);
            $address = implode(', ', $addressParts) ?: 'N/A';

            // Build full name with middle initial
            $middleInitial = $a->middle_name
                ? strtoupper(substr($a->middle_name, 0, 1)) . '.'
                : '';
            $fullName = trim(
                $a->first_name . ' ' .
                $middleInitial . ' ' .
                $a->last_name .
                ($a->suffix ? ', ' . $a->suffix : '')
            );

            // experience & training from JobApplication
            // if 0 or null => "None Required", otherwise show value with unit
            $experience = (!empty($app->experience) && $app->experience != 0)
                ? $app->experience . ' year(s)'
                : 'None Required';

            $training = (!empty($app->training) && $app->training != 0)
                ? $app->training . ' hour(s)'
                : 'None Required';

            return [
                'name'              => $fullName,
                'position_name'     => $p->name ?? 'N/A',
                'present_pos'       => $app->present_position ?? 'N/A',
                'education'         => $app->education ?? 'N/A',
                'experience'        => $experience,
                'training'          => $training,
                'eligibility'       => $app->eligibility ?? 'N/A',
                'other'             => $app->other_involvement ?? 'N/A',
                'requirements_file' => $app->requirements_file ?? 'N/A',
                'cp_number'         => $a->phone_number ?? 'N/A',
                'address'           => $address,
                'email'             => $u->email ?? 'N/A',
            ];
        })->values()->toArray();

        $hiringDate    = $this->selectedDate
            ? \Carbon\Carbon::parse($this->selectedDate)->format('F j, Y')
            : now()->format('F j, Y');
        $generatedDate = now()->format('F d, Y h:i A');
        $positionName  = $this->selectedPositionName;

        $html = view('print.faculty-hiring-print', compact(
            'applicants',
            'positionName',
            'hiringDate',
            'generatedDate'
        ))->render();

        $this->dispatch('openPrintTab', html: $html);
    }

    public function render()
    {
        // Unique position names
        $positionNames = Position::orderBy('name')
            ->pluck('name')
            ->unique()
            ->values();

        // Unique interview dates filtered by selected position
        $availableDates = collect();
        if ($this->selectedPositionName) {
            $positionIds = Position::where('name', $this->selectedPositionName)->pluck('id');
            $jobAppIds   = JobApplication::whereIn('position_id', $positionIds)->pluck('id');

            $availableDates = Evaluation::whereIn('job_application_id', $jobAppIds)
                ->orderBy('interview_date')
                ->pluck('interview_date')
                ->unique()
                ->values();
        }

        // No position selected — return empty paginator
        if (empty($this->selectedPositionName)) {
            $emptyPaginator = new LengthAwarePaginator(
                collect(), 0, 10, 1,
                ['path' => request()->url()]
            );

            return view('livewire.admin.scheduled-applicant', [
                'applications'   => $emptyPaginator,
                'pendingCount'   => 0,
                'positionNames'  => $positionNames,
                'availableDates' => $availableDates,
            ]);
        }

        $positionIds = Position::where('name', $this->selectedPositionName)->pluck('id');

        $baseQuery = JobApplication::with(['applicant.user', 'position', 'evaluation'])
            ->whereIn('position_id', $positionIds)
            ->whereHas('evaluation');

        if ($this->selectedDate) {
            $baseQuery->whereHas('evaluation', function ($q) {
                $q->where('interview_date', $this->selectedDate);
            });
        }

        $pendingCount = (clone $baseQuery)->count();
        $applications = $baseQuery->paginate(10);

        return view('livewire.admin.scheduled-applicant', [
            'applications'   => $applications,
            'pendingCount'   => $pendingCount,
            'positionNames'  => $positionNames,
            'availableDates' => $availableDates,
        ]);
    }
}