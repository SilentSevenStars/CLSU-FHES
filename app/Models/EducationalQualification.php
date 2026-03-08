<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationalQualification extends Model
{
    protected $fillable = [
        'subtotal',
        'q1_1',
        'q1_2',
        'q1_3',
    ];

    protected $casts = [
        'subtotal' => 'decimal:3',
        'q1_1' => 'decimal:3',
        'q1_2' => 'decimal:3',
        'q1_3' => 'decimal:3',
    ];

    /**
     * Get the evaluation that owns the educational qualification
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Get all NBC assignments for this educational qualification
     */
    public function nbcAssignments(): HasMany
    {
        return $this->hasMany(NbcAssignment::class);
    }

    /**
     * Get the total score
     */
    public function getTotalScoreAttribute(): float
    {
        return $this->subtotal ?? 0;
    }

    /**
     * Check if scores have been entered
     */
    public function hasScores(): bool
    {
        return !is_null($this->q1_1) &&
               !is_null($this->q1_2) &&
               !is_null($this->q1_3);
    }

    /**
     * Scope for completed evaluations
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('q1_1')
                     ->whereNotNull('q1_2')
                     ->whereNotNull('q1_3')
                     ->whereNotNull('subtotal');
    }
}

