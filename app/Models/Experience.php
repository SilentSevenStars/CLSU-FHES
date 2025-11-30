<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'education_qualification',
        'experience_type',
        'licensure_examination',
        'passing_licensure_examination',
        'place_board_exam',
        'professional_activities',
        'academic_performance',
        'publication',
        'school_graduate',
        'total_score',
    ];
}
