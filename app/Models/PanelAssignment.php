<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelAssignment extends Model
{  
    protected $fillable = [
        'panel_id',
        'interview_id',
        'experience_id',
        'performance_id',
        'status',
    ];
}
