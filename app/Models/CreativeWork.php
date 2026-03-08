<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreativeWork extends Model
{
    protected $fillable = [
        'subtotal',
        'q3_1_1',
        'q3_1_2_a',
        'q3_1_2_c',
        'q3_1_2_d',
        'q3_1_2_e',
        'q3_1_2_f',
        'q3_1_3_a',
        'q3_1_3_b',
        'q3_1_3_c',
        'q3_1_4',
    ];

    protected $casts = [
        'subtotal'  => 'decimal:3',
        'q3_1_1'   => 'decimal:3',
        'q3_1_2_a' => 'decimal:3',
        'q3_1_2_c' => 'decimal:3',
        'q3_1_2_d' => 'decimal:3',
        'q3_1_2_e' => 'decimal:3',
        'q3_1_2_f' => 'decimal:3',
        'q3_1_3_a' => 'decimal:3',
        'q3_1_3_b' => 'decimal:3',
        'q3_1_3_c' => 'decimal:3',
        'q3_1_4'   => 'decimal:3',
    ];

    public function professionalDevelopments(): HasMany
    {
        return $this->hasMany(ProfessionalDevelopment::class);
    }
}