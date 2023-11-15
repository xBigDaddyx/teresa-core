<?php

namespace Teresa\CartonBoxGuard\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Teresa\CartonBoxGuard\Models\Polybag;

class PolybagCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $carton;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Polybag $polybag)
    {
        $this->carton = $polybag->cartonBox;
    }

    // /**
    //  * Get the channels the event should broadcast on.
    //  *
    //  * @return \Illuminate\Broadcasting\Channel|array
    //  */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}
