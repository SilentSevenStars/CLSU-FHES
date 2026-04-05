<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpmsEntry extends Model
{
    protected $table = 'spms_entries';

    protected $fillable = [
        'spms_ipr_id',
        'section',
        'sort_order',
        'output',
        'success_indicators',
        'actual_accomplishments',
        'quality',
        'efficiency',
        'timeliness',
        'remarks',
    ];

    protected $casts = [
        'quality'    => 'float',
        'efficiency' => 'float',
        'timeliness' => 'float',
    ];

    public function ipr(): BelongsTo
    {
        return $this->belongsTo(SpmsIpr::class, 'spms_ipr_id');
    }

    /**
     * A⁴ average — computed, never stored.
     */
    public function getAverageAttribute(): ?float
    {
        if (is_null($this->quality) || is_null($this->efficiency) || is_null($this->timeliness)) {
            return null;
        }
        return round(($this->quality + $this->efficiency + $this->timeliness) / 3, 2);
    }

    public function isRated(): bool
    {
        return !is_null($this->quality) && !is_null($this->efficiency) && !is_null($this->timeliness);
    }
}