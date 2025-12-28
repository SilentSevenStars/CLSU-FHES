<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExperienceService extends Model
{
    protected $fillable = [
        'subtotal',
        'rs_2_1_1',
        'ep_2_1_1',
        'rs_2_1_2',
        'ep_2_1_2',
        'rs_2_2_1',
        'ep_2_2_1',
        'rs_2_3_1',
        'ep_2_3_1',
        'rs_2_3_2',
        'ep_2_3_2',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'rs_2_1_1' => 'decimal:2',
        'ep_2_1_1' => 'decimal:2',
        'rs_2_1_2' => 'decimal:2',
        'ep_2_1_2' => 'decimal:2',
        'rs_2_2_1' => 'decimal:2',
        'ep_2_2_1' => 'decimal:2',
        'rs_2_3_1' => 'decimal:2',
        'ep_2_3_1' => 'decimal:2',
        'rs_2_3_2' => 'decimal:2',
        'ep_2_3_2' => 'decimal:2',
    ];

    /**
     * Get all NBC assignments for this experience service
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
            ($this->rs_2_1_1 ?? 0) +
            ($this->rs_2_1_2 ?? 0) +
            ($this->rs_2_2_1 ?? 0) +
            ($this->rs_2_3_1 ?? 0) +
            ($this->rs_2_3_2 ?? 0),
            2
        );
    }

    /**
     * Calculate the EP score: MIN(RS Total, 25)
     * This is stored in the 'subtotal' field
     */
    public function getEpScoreAttribute(): float
    {
        return round(min($this->rsTotal, 25), 2);
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
        return !is_null($this->rs_2_1_1) &&
               !is_null($this->rs_2_1_2) &&
               !is_null($this->rs_2_2_1) &&
               !is_null($this->rs_2_3_1) &&
               !is_null($this->rs_2_3_2);
    }

    /**
     * Scope for completed evaluations
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('rs_2_1_1')
                     ->whereNotNull('rs_2_1_2')
                     ->whereNotNull('rs_2_2_1')
                     ->whereNotNull('rs_2_3_1')
                     ->whereNotNull('rs_2_3_2')
                     ->whereNotNull('subtotal');
    }
}