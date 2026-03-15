<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Casts\Encrypted;

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
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'present_position' => Encrypted::class,
        'education' => Encrypted::class,
        'eligibility' => Encrypted::class,
        'other_involvement' => Encrypted::class,
        'other_requirements' => Encrypted::class,
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
