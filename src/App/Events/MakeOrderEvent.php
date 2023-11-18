<?php

namespace App\Events;

use Domain\Purchases\Models\ApprovalRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MakeOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public ApprovalRequest $request;
    public array $data;
    /**
     * Create a new event instance.
     */
    public function __construct(array $data, ApprovalRequest $request)
    {
        $this->request = $request;
        $this->data = $data;
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
