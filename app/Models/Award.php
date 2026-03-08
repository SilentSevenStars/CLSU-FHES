<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Award extends Model
{
    protected $fillable = [
        'subtotal',
        'q3_4_a',
        'q3_4_b',
        'q3_4_c',
    ];

    protected $casts = [
        'subtotal' => 'decimal:3',
        'q3_4_a'  => 'decimal:3',
        'q3_4_b'  => 'decimal:3',
        'q3_4_c'  => 'decimal:3',
    ];

    public function professionalDevelopments(): HasMany
    {
        return $this->hasMany(ProfessionalDevelopment::class);
    }
}