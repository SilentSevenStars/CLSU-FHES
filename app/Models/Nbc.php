<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nbc extends Model
{
    protected $fillable = [
        'educational_qualification',
        'experience',
        'professional_development',
        'total_score',
    ];

    protected $casts = [
        'educational_qualification' => 'decimal:3',
        'experience' => 'decimal:3',
        'professional_development' => 'decimal:3',
        'total_score' => 'decimal:3',
    ];

    /**
     * Calculate and update total score
     */
    public function updateTotalScore(): void
    {
        $this->total_score = $this->educational_qualification 
            + $this->experience 
            + $this->professional_development;
        $this->save();
    }
}
