<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalDevelopment extends Model
{
    protected $guarded = [];

    protected function cap(float $value, float $max): float
    {
        return round(min($value, $max), 3);
    }

    /* ===== SECTION 3.1 (MAX 30) ===== */
    public function getSubtotal31Attribute(): float
    {
        return $this->cap(
            ($this->rs_3_1_1 ?? 0)
          + ($this->rs_3_1_2_a ?? 0)
          + ($this->rs_3_1_2_c ?? 0)
          + ($this->rs_3_1_2_d ?? 0)
          + ($this->rs_3_1_2_e ?? 0)
          + ($this->rs_3_1_2_f ?? 0)
          + ($this->rs_3_1_3_a ?? 0)
          + ($this->rs_3_1_3_b ?? 0)
          + ($this->rs_3_1_3_c ?? 0)
          + ($this->rs_3_1_4 ?? 0),
        30);
    }

    /* ===== SECTION 3.2.1 (MAX 10) ===== */
    public function getSubtotal321Attribute(): float
    {
        return $this->cap(
            ($this->rs_3_2_1_1_a ?? 0)
          + ($this->rs_3_2_1_1_b ?? 0)
          + ($this->rs_3_2_1_1_c ?? 0)
          + ($this->rs_3_2_1_2 ?? 0)
          + ($this->rs_3_2_1_3_a ?? 0)
          + ($this->rs_3_2_1_3_b ?? 0)
          + ($this->rs_3_2_1_3_c ?? 0),
        10);
    }

    /* ===== SECTION 3.2.2 (MAX 20) ===== */
    public function getSubtotal322Attribute(): float
    {
        return $this->cap(
            ($this->rs_3_2_2_1_a ?? 0)
          + ($this->rs_3_2_2_1_b ?? 0)
          + ($this->rs_3_2_2_1_c ?? 0)
          + ($this->rs_3_2_2_2 ?? 0)
          + ($this->rs_3_2_2_3 ?? 0)
          + ($this->rs_3_2_2_4 ?? 0)
          + ($this->rs_3_2_2_5 ?? 0)
          + ($this->rs_3_2_2_6 ?? 0)
          + ($this->rs_3_2_2_7 ?? 0),
        20);
    }

    /* ===== SECTION 3.3 (MAX 30) ===== */
    public function getSubtotal33Attribute(): float
    {
        return $this->cap(
            ($this->rs_3_3_1_a ?? 0)
          + ($this->rs_3_3_1_b ?? 0)
          + ($this->rs_3_3_1_c ?? 0)
          + ($this->rs_3_3_2 ?? 0)
          + ($this->rs_3_3_3_a_doctorate ?? 0)
          + ($this->rs_3_3_3_a_masters ?? 0)
          + ($this->rs_3_3_3_a_nondegree ?? 0)
          + ($this->rs_3_3_3_b_doctorate ?? 0)
          + ($this->rs_3_3_3_b_masters ?? 0)
          + ($this->rs_3_3_3_b_nondegree ?? 0)
          + ($this->rs_3_3_3_c_doctorate ?? 0)
          + ($this->rs_3_3_3_c_masters ?? 0)
          + ($this->rs_3_3_3_c_nondegree ?? 0)
          + ($this->rs_3_3_3_d_doctorate ?? 0)
          + ($this->rs_3_3_3_d_masters ?? 0)
          + ($this->rs_3_3_3_e ?? 0),
        30);
    }

    /* ===== SECTION 3.4 (NO MAX) ===== */
    public function getSubtotal34Attribute(): float
    {
        return round(
            ($this->rs_3_4_a ?? 0)
          + ($this->rs_3_4_b ?? 0)
          + ($this->rs_3_4_c ?? 0),
        3);
    }

    /* ===== SECTION 3.5 (MAX 5) ===== */
    public function getSubtotal35Attribute(): float
    {
        return $this->cap($this->rs_3_5_1 ?? 0, 5);
    }

    /* ===== SECTION 3.6 (MAX 10) ===== */
    public function getSubtotal36Attribute(): float
    {
        return $this->cap(
            ($this->rs_3_6_1_a ?? 0)
          + ($this->rs_3_6_1_b ?? 0)
          + ($this->rs_3_6_1_c ?? 0)
          + ($this->rs_3_6_1_d ?? 0),
        10);
    }

    /* ===== CUMULATIVE TOTALS BY PAGE ===== */
    
    // Page 1 Total (Section 3.1 only)
    public function getPage1TotalAttribute(): float
    {
        return $this->subtotal31;
    }

    // Page 2 Total (Page 1 + Sections 3.2.1 + 3.2.2)
    public function getPage2TotalAttribute(): float
    {
        return round(
            $this->subtotal31 
          + $this->subtotal321 
          + $this->subtotal322,
        3);
    }

    // Page 3 Total (All sections combined)
    public function getPage3TotalAttribute(): float
    {
        return $this->cap(
            $this->subtotal31
          + $this->subtotal321
          + $this->subtotal322
          + $this->subtotal33
          + $this->subtotal34
          + $this->subtotal35
          + $this->subtotal36,
        90);
    }

    /* ===== FINAL EP (MAX 90) ===== */
    public function getEpScoreAttribute(): float
    {
        return $this->page3Total; // Same as page 3 total
    }
}