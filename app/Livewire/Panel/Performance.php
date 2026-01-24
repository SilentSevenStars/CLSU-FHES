<?php

namespace App\Livewire\Panel;

use App\Models\Evaluation;
use App\Models\Performance as ModelsPerformance;
use App\Models\PersonalCompetence;
use App\Models\Skill;
use App\Models\PanelAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Performance extends Component
{
    public $evaluationId;
    public $interviewId;
    public $evaluation;
    public $applicant;
    public $position;
    public $jobApplication;

    public $question1 = '';
    public $question2 = '';
    public $question3 = '';
    public $question4 = '';
    public $question5 = '';

    public $personalQuestion1 = '';

    public $currentPage = 1;
    public $totalScore = 0;
    public $showApplicantModal = false;

    protected $enumValues = [
        'VS' => 5,
        'S' => 4,
        'F' => 3,
        'P' => 2,
        'NI' => 1
    ];

    public function mount($evaluationId, $interviewId)
    {
        $this->evaluationId = $evaluationId;
        $this->interviewId = $interviewId;
        $this->evaluation = Evaluation::with([
            'jobApplication.applicant.user',
            'jobApplication.position',
        ])->findOrFail($evaluationId);

        $this->jobApplication = $this->evaluation->jobApplication;
        $this->applicant = $this->jobApplication->applicant;
        $this->position = $this->jobApplication->position;

        $user = Auth::user();
        $panel = $user->panel;

        if ($panel) {
            $panelAssignment = PanelAssignment::updateOrCreate(
                [
                    'panel_id' => $panel->id,
                    'evaluation_id' => $evaluationId
                ],
                [
                    'status' => 'not yet'
                ]
            );

            // Load existing performance data if available
            if ($panelAssignment->performance_id) {
                $performance = ModelsPerformance::find($panelAssignment->performance_id);
                if ($performance) {
                    $skill = Skill::find($performance->skill_id);
                    $personalCompetence = PersonalCompetence::find($performance->personal_competence_id);

                    if ($skill) {
                        $this->question1 = $skill->question1;
                        $this->question2 = $skill->question2;
                        $this->question3 = $skill->question3;
                        $this->question4 = $skill->question4;
                        $this->question5 = $skill->question5;
                    }

                    if ($personalCompetence) {
                        $this->personalQuestion1 = $personalCompetence->question1;
                    }
                }
            }
        }
    }

    public function toggleApplicantModal()
    {
        $this->showApplicantModal = !$this->showApplicantModal;
    }

    public function nextPage()
    {
        if ($this->currentPage == 1) {
            $this->validate([
                'question1' => 'required|in:VS,S,F,P,NI',
                'question2' => 'required|in:VS,S,F,P,NI',
                'question3' => 'required|in:VS,S,F,P,NI',
                'question4' => 'required|in:VS,S,F,P,NI',
                'question5' => 'required|in:VS,S,F,P,NI',
            ], [
                'question1.required' => 'Please rate knowledgeability and mastery',
                'question2.required' => 'Please rate way of communication',
                'question3.required' => 'Please rate skill in initiating',
                'question4.required' => 'Please rate way of motivating',
                'question5.required' => 'Please rate skill in sustaining',
            ]);
        }

        $this->currentPage++;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function calculateSkillTotal()
    {
        return $this->enumValues[$this->question1] +
            $this->enumValues[$this->question2] +
            $this->enumValues[$this->question3] +
            $this->enumValues[$this->question4] +
            $this->enumValues[$this->question5];
    }

    public function calculatePersonalTotal()
    {
        return $this->enumValues[$this->personalQuestion1];
    }

    public function returnToInterview()
    {
        // Save current performance data before returning
        $user = Auth::user();
        $panel = $user->panel;

        if ($panel) {
            $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
                ->where('evaluation_id', $this->evaluationId)
                ->first();

            // Only save if there's data to save
            if (
                $this->question1 && $this->question2 && $this->question3 &&
                $this->question4 && $this->question5 &&
                ($this->currentPage == 2 ? $this->personalQuestion1 : true)
            ) {

                // Check if performance already exists to update instead of create
                if ($panelAssignment && $panelAssignment->performance_id) {
                    $performance = ModelsPerformance::find($panelAssignment->performance_id);

                    // Update existing skill
                    $skill = Skill::find($performance->skill_id);
                    $skill->update([
                        'question1' => $this->question1,
                        'question2' => $this->question2,
                        'question3' => $this->question3,
                        'question4' => $this->question4,
                        'question5' => $this->question5,
                    ]);

                    // Update personal competence if available
                    if ($this->personalQuestion1) {
                        $personalCompetence = PersonalCompetence::find($performance->personal_competence_id);
                        $personalCompetence->update([
                            'question1' => $this->personalQuestion1,
                        ]);

                        $skillTotal = $this->calculateSkillTotal();
                        $personalTotal = $this->calculatePersonalTotal();
                        $this->totalScore = $skillTotal + $personalTotal;

                        // Update performance
                        $performance->update([
                            'total_score' => $this->totalScore,
                        ]);
                    }
                } else {
                    // Create new records only if all required data exists
                    if ($this->personalQuestion1) {
                        $skill = Skill::create([
                            'question1' => $this->question1,
                            'question2' => $this->question2,
                            'question3' => $this->question3,
                            'question4' => $this->question4,
                            'question5' => $this->question5,
                        ]);

                        $personalCompetence = PersonalCompetence::create([
                            'question1' => $this->personalQuestion1,
                        ]);

                        $skillTotal = $this->calculateSkillTotal();
                        $personalTotal = $this->calculatePersonalTotal();
                        $this->totalScore = $skillTotal + $personalTotal;

                        $performance = ModelsPerformance::create([
                            'skill_id' => $skill->id,
                            'personal_competence_id' => $personalCompetence->id,
                            'total_score' => $this->totalScore,
                        ]);

                        if ($panelAssignment) {
                            $panelAssignment->update([
                                'performance_id' => $performance->id,
                            ]);
                        }
                    }
                }
            }
        }

        // Redirect to interview page 2 (last page)
        return redirect()->route('panel.interview', ['evaluationId' => $this->evaluationId])
            ->with('returnPage', 2);
    }

    public function confirmSubmission()
    {
        // Validate all fields
        $this->validate([
            'question1' => 'required|in:VS,S,F,P,NI',
            'question2' => 'required|in:VS,S,F,P,NI',
            'question3' => 'required|in:VS,S,F,P,NI',
            'question4' => 'required|in:VS,S,F,P,NI',
            'question5' => 'required|in:VS,S,F,P,NI',
            'personalQuestion1' => 'required|in:VS,S,F,P,NI',
        ], [
            'personalQuestion1.required' => 'Please rate composed behavior while teaching',
        ]);

        // Always show SweetAlert confirmation
        $this->dispatch('show-swal-confirm');
    }

    public function savePerformance()
    {
        $user = Auth::user();
        $panel = $user->panel;

        if ($panel) {
            $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
                ->where('evaluation_id', $this->evaluationId)
                ->first();

            // Check if performance already exists to update instead of create
            if ($panelAssignment && $panelAssignment->performance_id) {
                $performance = ModelsPerformance::find($panelAssignment->performance_id);

                // Update existing skill
                $skill = Skill::find($performance->skill_id);
                $skill->update([
                    'question1' => $this->question1,
                    'question2' => $this->question2,
                    'question3' => $this->question3,
                    'question4' => $this->question4,
                    'question5' => $this->question5,
                ]);

                // Update existing personal competence
                $personalCompetence = PersonalCompetence::find($performance->personal_competence_id);
                $personalCompetence->update([
                    'question1' => $this->personalQuestion1,
                ]);

                $skillTotal = $this->calculateSkillTotal();
                $personalTotal = $this->calculatePersonalTotal();
                $this->totalScore = $skillTotal + $personalTotal;

                // Update performance
                $performance->update([
                    'total_score' => $this->totalScore,
                ]);
            } else {
                // Create new records
                $skill = Skill::create([
                    'question1' => $this->question1,
                    'question2' => $this->question2,
                    'question3' => $this->question3,
                    'question4' => $this->question4,
                    'question5' => $this->question5,
                ]);

                $personalCompetence = PersonalCompetence::create([
                    'question1' => $this->personalQuestion1,
                ]);

                $skillTotal = $this->calculateSkillTotal();
                $personalTotal = $this->calculatePersonalTotal();
                $this->totalScore = $skillTotal + $personalTotal;

                $performance = ModelsPerformance::create([
                    'skill_id' => $skill->id,
                    'personal_competence_id' => $personalCompetence->id,
                    'total_score' => $this->totalScore,
                ]);

                if ($panelAssignment) {
                    $panelAssignment->update([
                        'performance_id' => $performance->id,
                    ]);
                }
            }

            // Always mark as complete after performance, regardless of position
            if ($panelAssignment) {
                $panelAssignment->update(['status' => 'complete']);
            }
        }

        // Dispatch browser event for success
        $this->dispatch('performance-saved');
    }

    public function render()
    {
        return view('livewire.panel.performance');
    }
}
