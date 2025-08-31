<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPresenceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $isOnline;

    public function __construct(User $user, $isOnline)
    {
        $this->user = $user;
        $this->isOnline = $isOnline;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('admin-presence');
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'role' => $this->user->role,
            ],
            'is_online' => $this->isOnline,
            'timestamp' => now()->toISOString()
        ];
    }

    public function broadcastAs()
    {
        return 'UserPresenceUpdated';
    }
}
