<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = [
        'subtotal',
        'q3_2_1_1_a',
        'q3_2_1_1_b',
        'q3_2_1_1_c',
        'q3_2_1_2',
        'q3_2_1_3_a',
        'q3_2_1_3_b',
        'q3_2_1_3_c',
        'q3_2_2_1_a',
        'q3_2_2_1_b',
        'q3_2_2_1_c',
        'q3_2_2_2',
        'q3_2_2_3',
        'q3_2_2_4',
        'q3_2_2_5',
        'q3_2_2_6',
        'q3_2_2_7',
    ];

    protected $casts = [
        'subtotal'    => 'decimal:3',
        'q3_2_1_1_a' => 'decimal:3',
        'q3_2_1_1_b' => 'decimal:3',
        'q3_2_1_1_c' => 'decimal:3',
        'q3_2_1_2'   => 'decimal:3',
        'q3_2_1_3_a' => 'decimal:3',
        'q3_2_1_3_b' => 'decimal:3',
        'q3_2_1_3_c' => 'decimal:3',
        'q3_2_2_1_a' => 'decimal:3',
        'q3_2_2_1_b' => 'decimal:3',
        'q3_2_2_1_c' => 'decimal:3',
        'q3_2_2_2'   => 'decimal:3',
        'q3_2_2_3'   => 'decimal:3',
        'q3_2_2_4'   => 'decimal:3',
        'q3_2_2_5'   => 'decimal:3',
        'q3_2_2_6'   => 'decimal:3',
        'q3_2_2_7'   => 'decimal:3',
    ];

    public function professionalDevelopments(): HasMany
    {
        return $this->hasMany(ProfessionalDevelopment::class);
    }
}