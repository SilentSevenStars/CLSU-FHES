<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NbcAssignment extends Model
{
    protected $fillable = [
        'nbc_committee_id',
        'evaluation_id',
        'educational_qualification_id',
        'experience_service_id',
        'professional_development_id',
        'nbc_id',
        'status',
        'type',
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string',
    ];

    /**
     * Get the NBC committee member assigned
     */
    public function nbcCommittee(): BelongsTo
    {
        return $this->belongsTo(NbcCommittee::class, 'nbc_committee_id');
    }

    /**
     * Get the evaluation
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Get the educational qualification
     */
    public function educationalQualification(): BelongsTo
    {
        return $this->belongsTo(EducationalQualification::class);
    }

    /**
     * Get the experience service
     */
    public function experienceService(): BelongsTo
    {
        return $this->belongsTo(ExperienceService::class);
    }

    /**
     * Get the professional development
     */
    public function professionalDevelopment(): BelongsTo
    {
        return $this->belongsTo(ProfessionalDevelopment::class);
    }

    public function nbc(): BelongsTo
    {
        return $this->belongsTo(Nbc::class);
    }

    /**
     * Check if assignment is for evaluator
     */
    public function isEvaluator(): bool
    {
        return $this->type === 'evaluate';
    }

    /**
     * Check if assignment is for verifier
     */
    public function isVerifier(): bool
    {
        return $this->type === 'verify';
    }

    /**
     * Check if assignment is complete
     */
    public function isComplete(): bool
    {
        return $this->status === 'complete';
    }

    /**
     * Mark assignment as complete
     */
    public function markAsComplete(): void
    {
        $this->update(['status' => 'complete']);
    }

    /**
     * Scope for pending assignments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for complete assignments
     */
    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }

    /**
     * Scope for evaluator assignments
     */
    public function scopeEvaluator($query)
    {
        return $query->where('type', 'evaluate');
    }

    /**
     * Scope for verifier assignments
     */
    public function scopeVerifier($query)
    {
        return $query->where('type', 'verify');
    }
}