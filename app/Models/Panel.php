<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'panel_position',
        'college',
        'department',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
