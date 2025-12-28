<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NbcCommittee extends Model
{
    use HasFactory;

    protected $table = 'nbc_committees';

    protected $fillable = [
        'user_id',
        'position',
    ];

    protected $casts = [
        'position' => 'string',
    ];

    /**
     * Get the user that belongs to this NBC committee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all assignments for this NBC committee member
     */
    // public function assignments(): HasMany
    // {
    //     return $this->hasMany(NbcAssignment::class, 'nbc_committee_id');
    // }

    /**
     * Get the position name formatted.
     */
    public function getPositionNameAttribute(): string
    {
        return ucfirst($this->position);
    }

    /**
     * Check if member is an evaluator
     */
    public function isEvaluator(): bool
    {
        return $this->position === 'evaluator';
    }

    /**
     * Check if member is a verifier
     */
    public function isVerifier(): bool
    {
        return $this->position === 'verifier';
    }

    /**
     * Get pending assignments count
     */
    public function getPendingAssignmentsCountAttribute(): int
    {
        return $this->assignments()->where('status', 'pending')->count();
    }

    /**
     * Get complete assignments count
     */
    public function getCompleteAssignmentsCountAttribute(): int
    {
        return $this->assignments()->where('status', 'complete')->count();
    }

    /**
     * Scope for evaluators only
     */
    public function scopeEvaluators($query)
    {
        return $query->where('position', 'evaluator');
    }

    /**
     * Scope for verifiers only
     */
    public function scopeVerifiers($query)
    {
        return $query->where('position', 'verifier');
    }
}