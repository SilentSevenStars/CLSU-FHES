<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'applicant_id',
        'subject',
        'message',
        'attachments',
        'is_read',
        'read_at',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'email_sent' => 'boolean',
        'read_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'subject' => 'encrypted',
        'message' => 'encrypted',
    ];

    /**
     * Get the applicant that owns the notification.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    /**
     * Normalize attachment metadata for views and downloads.
     */
    public function attachmentItems(): array
    {
        if (empty($this->attachments)) {
            return [];
        }

        // Laravel's 'array' cast automatically deserializes JSON
        // $this->attachments should be an array of attachment objects
        $items = [];

        if (is_array($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                if (is_array($attachment)) {
                    $items[] = [
                        'name' => $attachment['name'] ?? 'Unknown',
                        'path' => $attachment['path'] ?? null,
                        'size' => $attachment['size'] ?? 0,
                    ];
                }
            }
        }

        return $items;
    }


    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}