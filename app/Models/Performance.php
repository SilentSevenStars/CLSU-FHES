<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $fillable = [
        'skill_id',
        'personal_competence_id',
        'total_score',
    ];
}
