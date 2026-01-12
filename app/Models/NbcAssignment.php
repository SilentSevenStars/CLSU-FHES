<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NbcAssignment extends Model
{
    use HasFactory;

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
     * Get the NBC committee that owns the assignment
     */
    public function nbcCommittee(): BelongsTo
    {
        return $this->belongsTo(NbcCommittee::class);
    }

    /**
     * Get the evaluation that owns the assignment
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

    /**
     * Get the NBC record
     */
    public function nbc(): BelongsTo
    {
        return $this->belongsTo(Nbc::class);
    }

    /**
     * Check if this is an evaluator assignment
     */
    public function isEvaluator(): bool
    {
        return $this->type === 'evaluate';
    }

    /**
     * Check if this is a verifier assignment
     */
    public function isVerifier(): bool
    {
        return $this->type === 'verify';
    }

    /**
     * Create or update NBC record with current scores
     */
    public function updateNbcScores(): void
    {
        if (!$this->nbc_id) {
            $nbc = Nbc::create([]);
            $this->update(['nbc_id' => $nbc->id]);
            $this->load('nbc');
        }

        $this->nbc->updateScoresFromAssignment($this);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($assignment) {
            // Create NBC record when assignment is created
            if (!$assignment->nbc_id) {
                $nbc = Nbc::create([]);
                $assignment->update(['nbc_id' => $nbc->id]);
            }
        });
    }
}