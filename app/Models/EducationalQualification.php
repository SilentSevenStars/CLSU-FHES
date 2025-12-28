<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationalQualification extends Model
{
    protected $fillable = [
        'subtotal',
        'rs_1_1',
        'ep_1_1',
        'rs_1_2',
        'ep_1_2',
        'rs_1_3',
        'ep_1_3',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'rs_1_1' => 'decimal:2',
        'ep_1_1' => 'decimal:2',
        'rs_1_2' => 'decimal:2',
        'ep_1_2' => 'decimal:2',
        'rs_1_3' => 'decimal:2',
        'ep_1_3' => 'decimal:2',
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
     * Calculate the total RS score
     */
    public function getRsTotalAttribute(): float
    {
        return round(
            ($this->rs_1_1 ?? 0) +
            ($this->rs_1_2 ?? 0) +
            ($this->rs_1_3 ?? 0),
            2
        );
    }

    /**
     * Calculate the EP score: MIN(RS Total, 85)
     * This is stored in the 'subtotal' field
     */
    public function getEpScoreAttribute(): float
    {
        return round(min($this->rsTotal, 85), 2);
    }

    /**
     * Get the total score (alias for backward compatibility)
     */
    public function getTotalScoreAttribute(): float
    {
        return $this->subtotal ?? $this->epScore;
    }

    /**
     * Check if scores have been entered
     */
    public function hasScores(): bool
    {
        return !is_null($this->rs_1_1) &&
               !is_null($this->rs_1_2) &&
               !is_null($this->rs_1_3);
    }

    /**
     * Scope for completed evaluations
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('rs_1_1')
                     ->whereNotNull('rs_1_2')
                     ->whereNotNull('rs_1_3')
                     ->whereNotNull('subtotal');
    }
}