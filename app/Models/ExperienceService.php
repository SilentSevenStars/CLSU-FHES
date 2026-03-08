<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExperienceService extends Model
{
    protected $fillable = [
        'subtotal',
        'q2_1_1',
        'q2_1_2',
        'q2_2_1',
        'q2_3_1',
        'q2_3_2',
    ];

    protected $casts = [
        'subtotal' => 'decimal:3',
        'q2_1_1' => 'decimal:3',
        'q2_1_2' => 'decimal:3',
        'q2_2_1' => 'decimal:3',
        'q2_3_1' => 'decimal:3',
        'q2_3_2' => 'decimal:3',
    ];

    /**
     * Get all NBC assignments for this experience service
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
        return !is_null($this->q2_1_1) &&
               !is_null($this->q2_1_2) &&
               !is_null($this->q2_2_1) &&
               !is_null($this->q2_3_1) &&
               !is_null($this->q2_3_2);
    }

    /**
     * Scope for completed evaluations
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('q2_1_1')
                     ->whereNotNull('q2_1_2')
                     ->whereNotNull('q2_2_1')
                     ->whereNotNull('q2_3_1')
                     ->whereNotNull('q2_3_2')
                     ->whereNotNull('subtotal');
    }
}

