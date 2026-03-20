<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Casts\Encrypted;

class NbcCommittee extends Model
{
    use HasFactory;

    protected $table = 'nbc_committees';

    protected $fillable = [
        'user_id',
        'position',
    ];

    /**
     * Encrypt the position column just like User::name and User::email.
     */
    protected function casts(): array
    {
        return [
            'position' => Encrypted::class,
        ];
    }

    // ── Valid position constants ──────────────────────────────────────────────

    const POSITION_CHAIRPERSON = 'CLSU NBC 461 Chairperson';
    const POSITION_EVALUATOR   = 'Evaluator';

    public static function validPositions(): array
    {
        return [
            self::POSITION_CHAIRPERSON,
            self::POSITION_EVALUATOR,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(NbcAssignment::class, 'nbc_committee_id');
    }


    public function getPositionNameAttribute(): string
    {
        return $this->position ?? '';
    }


    public function isChairperson(): bool
    {
        return $this->position === self::POSITION_CHAIRPERSON;
    }

    public function isEvaluator(): bool
    {
        return $this->position === self::POSITION_EVALUATOR;
    }


    public function getPendingAssignmentsCountAttribute(): int
    {
        return $this->assignments()->where('status', 'pending')->count();
    }

    public function getCompleteAssignmentsCountAttribute(): int
    {
        return $this->assignments()->where('status', 'complete')->count();
    }

    public function scopeChairpersons($query)
    {
        return $query; 
    }

    public function scopeEvaluators($query)
    {
        return $query; 
    }


    public static function chairpersonExists(?int $excludeUserId = null): bool
    {
        $query = self::all();

        if ($excludeUserId !== null) {
            $query = $query->reject(fn ($m) => $m->user_id === $excludeUserId);
        }

        return $query->contains(fn ($m) => $m->isChairperson());
    }
}