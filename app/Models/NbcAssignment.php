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
        'status',
        'evaluation_date',
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

}
