<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionRank extends Model
{
    protected $fillable = [
        'name',
        // 'salary_grade',
        // 'point_bracket_minimum',
        // 'point_bracket_maximum',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
