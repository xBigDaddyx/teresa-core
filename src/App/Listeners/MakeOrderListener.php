<?php

namespace App\Listeners;

use App\Events\MakeOrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MakeOrderListener
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
    public function handle(MakeOrderEvent $event): void
    {
        dd($event->request->approvable->requestItems);
    }
}
