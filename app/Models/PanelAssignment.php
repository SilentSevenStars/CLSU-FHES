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
        'evaluation_id'
    ];

    public function performance()
    {
        return $this->belongsTo(Performance::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}
