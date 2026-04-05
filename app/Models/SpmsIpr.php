<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpmsIpr extends Model
{
    protected $table = 'spms_iprs';

    protected $fillable = [
        'applicant_id',
        'evaluation_period',
        'period_start',
        'period_end',
        'status',
        'strategic_avg',
        'core_avg',
        'support_avg',
        'final_weighted_rating',
        'adjectival_rating',
        'immediate_superior',
        'discussed_date',
        'comments',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'discussed_date' => 'date',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(SpmsEntry::class, 'spms_ipr_id')->orderBy('section')->orderBy('sort_order');
    }

    public function strategicEntries(): HasMany
    {
        return $this->hasMany(SpmsEntry::class, 'spms_ipr_id')->where('section', 'A')->orderBy('sort_order');
    }

    public function coreEntries(): HasMany
    {
        return $this->hasMany(SpmsEntry::class, 'spms_ipr_id')->where('section', 'B')->orderBy('sort_order');
    }

    public function supportEntries(): HasMany
    {
        return $this->hasMany(SpmsEntry::class, 'spms_ipr_id')->where('section', 'C')->orderBy('sort_order');
    }

    /**
     * Recompute all averages and weighted final rating, then save.
     */
    public function recompute(): void
    {
        $this->strategic_avg = $this->computeSectionAvg('A');
        $this->core_avg      = $this->computeSectionAvg('B');
        $this->support_avg   = $this->computeSectionAvg('C');

        if (!is_null($this->strategic_avg) && !is_null($this->core_avg) && !is_null($this->support_avg)) {
            $this->final_weighted_rating = round(
                ($this->strategic_avg * 0.40) +
                ($this->core_avg      * 0.40) +
                ($this->support_avg   * 0.20),
                3
            );
            $this->adjectival_rating = $this->resolveAdjectival($this->final_weighted_rating);
        }

        $this->save();
    }

    private function computeSectionAvg(string $section): ?float
    {
        $entries = $this->entries()->where('section', $section)
            ->whereNotNull('quality')
            ->whereNotNull('efficiency')
            ->whereNotNull('timeliness')
            ->get();

        if ($entries->isEmpty()) return null;

        $rowAverages = $entries->map(fn($e) => ($e->quality + $e->efficiency + $e->timeliness) / 3);
        return round($rowAverages->avg(), 3);
    }

    public static function resolveAdjectival(float $score): string
    {
        return match(true) {
            $score >= 4.80 => 'Outstanding',
            $score >= 3.80 => 'Very Satisfactory',
            $score >= 2.80 => 'Satisfactory',
            $score >= 1.80 => 'Unsatisfactory',
            default        => 'Poor',
        };
    }
}