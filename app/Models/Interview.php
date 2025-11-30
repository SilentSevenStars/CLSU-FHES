<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'general_appearance',
        'manner_of_speaking',
        'physical_conditions',
        'alertness',
        'self_confidence',
        'ability_to_present_ideas',
        'maturity_of_judgement',
        'total_score',
    ];
}
