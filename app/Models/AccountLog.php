<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'activity',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}