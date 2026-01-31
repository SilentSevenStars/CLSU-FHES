<?php

namespace App\Livewire\Applicant;

use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function loadApplications()
    {
        
    }

    public function render()
    {
        $applicant = Applicant::where('user_id', Auth::id())->first();
        
        $applications = collect([]);
        if ($applicant) {
            $applications = $applicant->jobApplications()
                ->with([
                    'position.department',
                    'evaluation.panelAssignments.panel.user',
                ])
                ->get()
                ->map(function ($application) {
                    $interviewSchedule = null;
                    if ($application->evaluation) {
                        $interviewSchedule = [
                            'date' => $application->evaluation->interview_date,
                            'room' => $application->evaluation->interview_room,
                        ];
                    }

                    $evaluationStatus = $this->getEvaluationStatus($application);

                    return (object) array_merge($application->toArray(), [
                        'interview_schedule' => $interviewSchedule,
                        'evaluation_status' => $evaluationStatus,
                    ]);
                });
        }

        return view('livewire.applicant.dashboard', [
            'applications' => $applications,
        ]);
    }

    /**
     * Check if evaluation is complete based on panel assignments
     */
    private function getEvaluationStatus($application)
    {
        if (!$application->evaluation) {
            return [
                'is_complete' => false,
                'status' => 'No Evaluation',
                'is_late' => false,
            ];
        }

        $panelAssignments = $application->evaluation->panelAssignments;

        if ($panelAssignments->count() === 0) {
            $interviewDate = $application->evaluation->interview_date;
            $isLate = $interviewDate && now()->isAfter($interviewDate);
            
            return [
                'is_complete' => false,
                'status' => $isLate ? 'Overdue' : 'Pending',
                'is_late' => $isLate,
            ];
        }

        $allComplete = $panelAssignments->every(function ($assignment) {
            return $assignment->status === 'complete';
        });

        $interviewDate = $application->evaluation->interview_date;
        $isLate = false;
        
        if (!$allComplete && $interviewDate && now()->isAfter($interviewDate)) {
            $isLate = true;
        }

        return [
            'is_complete' => $allComplete,
            'status' => $allComplete ? 'Complete' : ($isLate ? 'Overdue' : 'In Progress'),
            'completed_count' => $panelAssignments->where('status', 'complete')->count(),
            'total_count' => $panelAssignments->count(),
            'is_late' => $isLate,
        ];
    }
}