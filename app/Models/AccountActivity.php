<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountActivity extends Model
{
    public $timestamps = false;

    protected $table = 'account_activities';

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