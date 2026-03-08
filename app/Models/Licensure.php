<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Licensure extends Model
{
    protected $fillable = [
        'subtotal',
        'q3_6_1_a',
        'q3_6_1_b',
        'q3_6_1_c',
        'q3_6_1_d',
    ];

    protected $casts = [
        'subtotal'  => 'decimal:3',
        'q3_6_1_a' => 'decimal:3',
        'q3_6_1_b' => 'decimal:3',
        'q3_6_1_c' => 'decimal:3',
        'q3_6_1_d' => 'decimal:3',
    ];

    public function professionalDevelopments(): HasMany
    {
        return $this->hasMany(ProfessionalDevelopment::class);
    }
}