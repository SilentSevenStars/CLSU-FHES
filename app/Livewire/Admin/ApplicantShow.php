<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ApplicantShow extends Component
{
    public $application;
    public $status;
    public $interview_date;
    public $interview_room;

    public function mount($job_application_id)
    {
        $this->application = JobApplication::with(['applicant.user', 'position'])
            ->findOrFail($job_application_id);
    }

    public function getCanReviewProperty()
    {
        $today = now()->toDateString();
        $position = $this->application->position;

        if (!$position->start_date || !$position->end_date) {
            return false;
        }

        return $today > $position->end_date;
    }

    public function submitReview()
    {
        $this->validate([
            'status' => 'required|in:approve,decline',
            'interview_date' => $this->status === 'approve' ? 'required|date' : 'nullable',
            'interview_room' => $this->status === 'approve' ? 'required|string|max:255' : 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $this->application->update([
                'status' => $this->status,
                'reviewed_at' => now(),
            ]);

            if ($this->status === 'approve') {
                $this->application->evaluation()->updateOrCreate([], [
                    'interview_date' => $this->interview_date,
                    'interview_room' => $this->interview_room,
                    'total_score' => 0,
                    'rank' => 0
                ]);
            }

            DB::commit();

            return redirect(route('admin.applicant'))->with('success', 'Application reviewed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong while reviewing the application: ' . $e->getMessage());
            return back();
        }
    }

    public function render()
    {
        return view('livewire.admin.applicant-show', [
            'application' => $this->application,
            'canReview' => $this->canReview,
        ])->layout('layouts.app');
    }
}
