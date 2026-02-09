<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'phone_number',
        'address',
        'region',
        'province',
        'city',
        'barangay',
        'street',
        'postal_code',
        'position',
        'hired',
        'user_id',
    ];

    protected $casts = [
        'hired' => 'boolean',
    ];

    /**
     * Get the user that owns the applicant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all job applications for the applicant.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get all notifications for the applicant.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the full name of the applicant.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Get the display name for the region.
     */
    public function getRegionDisplayAttribute(): ?string
    {
        // Return null if region is not set
        if (empty($this->region)) {
            return null;
        }

        $regionMapping = [
            'Ilocos Region' => 'Region I',
            'Cagayan Valley' => 'Region II',
            'Central Luzon' => 'Region III',
            'CALABARZON' => 'Region IV-A',
            'MIMAROPA Region' => 'Region IV-B',
            'Bicol Region' => 'Region V',
            'Western Visayas' => 'Region VI',
            'Central Visayas' => 'Region VII',
            'Eastern Visayas' => 'Region VIII',
            'Zamboanga Peninsula' => 'Region IX',
            'Northern Mindanao' => 'Region X',
            'Davao Region' => 'Region XI',
            'SOCCSKSARGEN' => 'Region XII',
            'NCR' => 'NCR',
            'CAR' => 'CAR',
            'Caraga' => 'Region XVI',
            'BARMM' => 'BARMM',
        ];

        return $regionMapping[$this->region] ?? $this->region;
    }
}
