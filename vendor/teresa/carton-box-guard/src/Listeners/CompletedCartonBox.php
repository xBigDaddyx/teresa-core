<?php

namespace Teresa\CartonBoxGuard\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Teresa\CartonBoxGuard\Events\PolybagCreated;
use Teresa\CartonBoxGuard\Models\CartonBox;

class CompletedCartonBox
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(PolybagCreated $event)
    {
        $max_quantity = $event->carton->quantity;
        $carton = CartonBox::find($event->carton->id);
        $polybags_count = $carton->polybags->count();
        if ($polybags_count === $max_quantity) {
            $carton->is_completed = true;
            $carton->completed_by = Auth::user()->id;
            $carton->completed_at = Carbon::now();
            $carton->lock();
            $carton->save();
        }
    }
}
