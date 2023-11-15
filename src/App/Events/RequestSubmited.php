<?php

namespace App\Events;

use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\Request;
use Domain\Users\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestSubmited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Request $request;
    public string $type;
    public User $user;
    /**
     * Create a new event instance.
     */
    public function __construct(Request $request, string $type, User $user)
    {
        $this->request = $request;
        $this->type = $type;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
