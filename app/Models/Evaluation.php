<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'interview_date',
        'interview_room',
        'total_score',
        'rank',
        'job_application_id',
        'educational_score',
        'experience_score',
        'professional_dev_score',
        'evaluator_remarks',
        'verifier_remarks'
    ];

    protected $casts = [
        'interview_date' => 'date',
        'total_score' => 'decimal:2',
        'educational_score' => 'decimal:2',
        'experience_score' => 'decimal:2',
        'professional_dev_score' => 'decimal:2',
        'rank' => 'integer'
    ];

    /**
     * Get the job application that owns the evaluation
     */
    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    /**
     * Get the educational qualification for the evaluation
     */
    public function educationalQualification(): HasOne
    {
        return $this->hasOne(EducationalQualification::class);
    }

    /**
     * Get the experience service for the evaluation
     */
    public function experienceService(): HasOne
    {
        return $this->hasOne(ExperienceService::class);
    }

    /**
     * Get the professional development for the evaluation
     */
    public function professionalDevelopment(): HasOne
    {
        return $this->hasOne(ProfessionalDevelopment::class);
    }

    /**
     * Get the panel assignments for the evaluation
     */
    public function panelAssignments(): HasMany
    {
        return $this->hasMany(PanelAssignment::class);
    }

    /**
     * Calculate and update total score from all components
     * 
     * @return void
     */
    public function calculateTotalScore(): void
    {
        // Get scores from each component using ORM relationships
        $educationalScore = $this->educationalQualification?->calculateScore() ?? 0;
        $experienceScore = $this->experienceService?->calculateScore() ?? 0;
        $professionalDevScore = $this->professionalDevelopment?->calculateScore() ?? 0;

        // Update the evaluation scores
        $this->educational_score = $educationalScore;
        $this->experience_score = $experienceScore;
        $this->professional_dev_score = $professionalDevScore;
        $this->total_score = $educationalScore + $experienceScore + $professionalDevScore;

        $this->save();

        // Update component subtotals using ORM
        $this->educationalQualification?->update(['subtotal' => $educationalScore]);
        $this->experienceService?->update(['subtotal' => $experienceScore]);
        $this->professionalDevelopment?->update(['subtotal' => $professionalDevScore]);
    }

    /**
     * Check if evaluation is complete
     * 
     * @return bool
     */
    public function isComplete(): bool
    {
        return !is_null($this->total_score) &&
            $this->educationalQualification()->exists() &&
            $this->experienceService()->exists() &&
            $this->professionalDevelopment()->exists();
    }

    /**
     * Check if evaluation has all NBC components
     * 
     * @return bool
     */
    public function hasAllNbcComponents(): bool
    {
        return $this->educationalQualification()->exists() &&
            $this->experienceService()->exists() &&
            $this->professionalDevelopment()->exists();
    }

    /**
     * Get the applicant through job application
     * 
     * @return \App\Models\Applicant|null
     */
    public function getApplicantAttribute()
    {
        return $this->jobApplication?->applicant;
    }

    /**
     * Get the position through job application
     * 
     * @return \App\Models\Position|null
     */
    public function getPositionAttribute()
    {
        return $this->jobApplication?->position;
    }

    /**
     * Scope query to only complete evaluations
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComplete($query)
    {
        return $query->whereNotNull('total_score');
    }

    /**
     * Scope query to only pending evaluations
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->whereNull('total_score');
    }

    /**
     * Scope query for today's evaluations
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('interview_date', now()->toDateString());
    }

    /**
     * Scope query for evaluations with allowed professor positions
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForProfessorPositions($query)
    {
        $allowedPositions = [
            'Professor I',
            'Professor II',
            'Professor III',
            'Professor IV',
            'Professor V',
            'College Professor',
            'University Professor'
        ];

        return $query->whereHas('jobApplication.position', function ($q) use ($allowedPositions) {
            $q->whereIn('name', $allowedPositions);
        });
    }
}
