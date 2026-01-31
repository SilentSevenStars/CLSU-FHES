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

                    // Calculate evaluation completion status from panel assignments
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


    private function getEvaluationStatus($application)
    {
        if (!$application->evaluation) {
            return [
                'is_complete' => false,
                'status' => 'No Evaluation',
            ];
        }

        $panelAssignments = $application->evaluation->panelAssignments;
        
        if ($panelAssignments->count() === 0) {
            return [
                'is_complete' => false,
                'status' => 'Pending',
            ];
        }

        $allComplete = $panelAssignments->every(function ($assignment) {
            return $assignment->status === 'complete';
        });

        return [
            'is_complete' => $allComplete,
            'status' => $allComplete ? 'Complete' : 'In Progress',
            'completed_count' => $panelAssignments->where('status', 'complete')->count(),
            'total_count' => $panelAssignments->count(),
        ];
    }
}