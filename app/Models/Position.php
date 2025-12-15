<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'department',
        'status',
        'start_date',
        'end_date',
        'specialization',
        'education',
        'experience',
        'training',
        'eligibility',
    ];

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
