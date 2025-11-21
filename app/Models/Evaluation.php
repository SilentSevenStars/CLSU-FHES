<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'interview_date',
        'interview_room',
        'total_score',
        'rank',
    ];

    protected $casts = [
        'interview_date' => 'date',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function panelAssignments()
    {
        return $this->hasMany(\App\Models\PanelAssignment::class);
    }
}
