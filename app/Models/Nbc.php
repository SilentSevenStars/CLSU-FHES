<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nbc extends Model
{
    protected $fillable = [
        'educational_qualification',
        'experience',
        'professional_development',
        'total_score',
    ];

    protected $casts = [
        'educational_qualification' => 'decimal:3',
        'experience' => 'decimal:3',
        'professional_development' => 'decimal:3',
        'total_score' => 'decimal:3',
    ];

    /**
     * Get all NBC assignments for this NBC record
     */
    public function nbcAssignments(): HasMany
    {
        return $this->hasMany(NbcAssignment::class);
    }

    /**
     * Calculate and update total score
     */
    public function updateTotalScore(): void
    {
        $this->total_score = $this->educational_qualification 
            + $this->experience 
            + $this->professional_development;
        $this->save();
    }

    /**
     * Update scores from assignment data
     */
    public function updateScoresFromAssignment(NbcAssignment $assignment): void
    {
        if ($assignment->educationalQualification) {
            $this->educational_qualification = $assignment->educationalQualification->subtotal ?? 0;
        }

        if ($assignment->experienceService) {
            $this->experience = $assignment->experienceService->subtotal ?? 0;
        }

        if ($assignment->professionalDevelopment) {
            $this->professional_development = $assignment->professionalDevelopment->ep_score ?? 0;
        }

        $this->updateTotalScore();
    }
}
