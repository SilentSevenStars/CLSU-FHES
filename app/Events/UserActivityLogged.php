<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivityLogged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int    $logId,
        public readonly int    $userId,
        public readonly string $activity,
        public readonly string $datetime,
        public readonly string $userName,
        public readonly string $userEmail,
        public readonly string $userRole,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('account-logs')];
    }

    public function broadcastAs(): string
    {
        return 'UserActivityLogged';
    }
}