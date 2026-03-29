<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivityRecorded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int    $activityId,
        public readonly int    $userId,
        public readonly string $activity,
        public readonly string $datetime,
        public readonly string $userName,
        public readonly string $userEmail,
        public readonly string $userRole,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('account-activities')];
    }

    public function broadcastAs(): string
    {
        return 'UserActivityRecorded';
    }
}