<?php

namespace App\Livewire\Panel;

use App\Models\Evaluation;
use App\Models\Interview as ModelsInterview;
use App\Models\PanelAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Interview extends Component
{
    public $evaluationId;
    public $evaluation;

    public $general_appearance = 0;
    public $manner_of_speaking = 0;
    public $physical_conditions = 0;
    public $alertness = 0;
    public $self_confidence = 0;
    public $ability_to_present_ideas = 0;
    public $maturity_of_judgement = 0;

    public $currentPage = 1;
    public $totalScore = 0;

    public function mount($evaluationId)
    {
        $user = Auth::user();
        $panel = $user->panel;

        // Only allow access if user has a panel record
        if (!$panel) {
            abort(403, 'You are not registered as a panel member.');
        }

        $this->evaluationId = $evaluationId;
        $this->evaluation = Evaluation::findOrFail($evaluationId);

        PanelAssignment::updateOrCreate(
            [
                'panel_id' => $panel->id,
                'evaluation_id' => $evaluationId
            ],
            [
                'status' => 'not yet'
            ]
        );
    }

    public function nextPage()
    {
        if ($this->currentPage == 1) {
            $this->validate([
                'general_appearance' => 'required|integer|min:1|max:5',
                'manner_of_speaking' => 'required|integer|min:1|max:5',
                'physical_conditions' => 'required|integer|min:1|max:5',
                'alertness' => 'required|integer|min:1|max:5',
            ], [
                'general_appearance.required' => 'Please rate General Appearance',
                'manner_of_speaking.required' => 'Please rate Manner of Speaking',
                'physical_conditions.required' => 'Please rate Physical Conditioning',
                'alertness.required' => 'Please rate Alertness',
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

    public function calculateTotal()
    {
        $this->totalScore = $this->general_appearance +
            $this->manner_of_speaking +
            $this->physical_conditions +
            $this->alertness +
            $this->self_confidence +
            $this->ability_to_present_ideas +
            $this->maturity_of_judgement;
    }

    public function saveInterview()
    {
        $this->validate([
            'general_appearance' => 'required|integer|min:1|max:5',
            'manner_of_speaking' => 'required|integer|min:1|max:5',
            'physical_conditions' => 'required|integer|min:1|max:5',
            'alertness' => 'required|integer|min:1|max:5',
            'self_confidence' => 'required|integer|min:1|max:5',
            'ability_to_present_ideas' => 'required|integer|min:1|max:5',
            'maturity_of_judgement' => 'required|integer|min:1|max:5',
        ], [
            'self_confidence.required' => 'Please rate Self Confidence',
            'ability_to_present_ideas.required' => 'Please rate Ability to Present Ideas',
            'maturity_of_judgement.required' => 'Please rate Maturity of Judgement',
        ]);

        $this->calculateTotal();

        $interview = ModelsInterview::create([
            'general_appearance' => $this->general_appearance,
            'manner_of_speaking' => $this->manner_of_speaking,
            'physical_conditions' => $this->physical_conditions,
            'alertness' => $this->alertness,
            'self_confidence' => $this->self_confidence,
            'ability_to_present_ideas' => $this->ability_to_present_ideas,
            'maturity_of_judgement' => $this->maturity_of_judgement,
            'total_score' => $this->totalScore,
        ]);

        $user = Auth::user();
        $panel = $user->panel;
        if ($panel) {
            $panelAssignment = PanelAssignment::where('panel_id', $panel->id)
                ->where('evaluation_id', $this->evaluationId)
                ->first();
            if ($panelAssignment) {
                $panelAssignment->update(['interview_id' => $interview->id]);
            }
        }

        return redirect()->route('panel.performance', [
            'evaluationId' => $this->evaluationId,
            'interviewId' => $interview->id
        ]);
    }

    public function render()
    {
        return view('livewire.panel.interview')
            ->layout('layouts.app');
    }
}
