<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'college_id',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
