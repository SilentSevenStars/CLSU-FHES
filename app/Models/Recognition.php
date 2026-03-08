<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recognition extends Model
{
    protected $fillable = [
        'subtotal',
        'q3_3_1_a',
        'q3_3_1_b',
        'q3_3_1_c',
        'q3_3_2',
        'q3_3_3_a_doctorate',
        'q3_3_3_a_masters',
        'q3_3_3_a_nondegree',
        'q3_3_3_b_doctorate',
        'q3_3_3_b_masters',
        'q3_3_3_b_nondegree',
        'q3_3_3_c_doctorate',
        'q3_3_3_c_masters',
        'q3_3_3_c_nondegree',
        'q3_3_3_d_doctorate',
        'q3_3_3_d_masters',
        'q3_3_3_e',
    ];

    protected $casts = [
        'subtotal'             => 'decimal:3',
        'q3_3_1_a'            => 'decimal:3',
        'q3_3_1_b'            => 'decimal:3',
        'q3_3_1_c'            => 'decimal:3',
        'q3_3_2'              => 'decimal:3',
        'q3_3_3_a_doctorate'  => 'decimal:3',
        'q3_3_3_a_masters'    => 'decimal:3',
        'q3_3_3_a_nondegree'  => 'decimal:3',
        'q3_3_3_b_doctorate'  => 'decimal:3',
        'q3_3_3_b_masters'    => 'decimal:3',
        'q3_3_3_b_nondegree'  => 'decimal:3',
        'q3_3_3_c_doctorate'  => 'decimal:3',
        'q3_3_3_c_masters'    => 'decimal:3',
        'q3_3_3_c_nondegree'  => 'decimal:3',
        'q3_3_3_d_doctorate'  => 'decimal:3',
        'q3_3_3_d_masters'    => 'decimal:3',
        'q3_3_3_e'            => 'decimal:3',
    ];

    public function professionalDevelopments(): HasMany
    {
        return $this->hasMany(ProfessionalDevelopment::class);
    }
}