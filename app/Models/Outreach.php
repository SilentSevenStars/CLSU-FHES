<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outreach extends Model
{
    protected $fillable = [
        'subtotal',
        'q3_3_5_1',
    ];

    protected $casts = [
        'subtotal'  => 'decimal:3',
        'q3_3_5_1' => 'decimal:3',
    ];

    public function professionalDevelopments(): HasMany
    {
        return $this->hasMany(ProfessionalDevelopment::class);
    }
}