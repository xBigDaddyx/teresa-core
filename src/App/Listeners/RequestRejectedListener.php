<?php

namespace App\Listeners;

use App\Events\RequestRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RequestRejectedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RequestRejected $event): void
    {
        //
    }
}
