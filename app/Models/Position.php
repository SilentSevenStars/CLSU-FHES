<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'college',
        'department',
        'status',
        'start_date',
        'end_date',
    ];

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
