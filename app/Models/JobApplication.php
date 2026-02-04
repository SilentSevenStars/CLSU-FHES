<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'present_position',
        'education',
        'experience',
        'training',
        'eligibility',
        'other_involvement',
        'requirements_file',
        'applicant_id',
        'position_id',
        'status',
        'reviewed_at',
        'archive',
        'hired',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }
}
