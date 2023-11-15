<?php

namespace App\Http\Controllers\Kanban;

use Domain\Kanban\Models\PlanQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Support\Controllers\Controller;

class PlanQueueController extends Controller
{
    public function switch(PlanQueue $oldQueue, PlanQueue $newQueue)
    {
        return [
            'oldQueue' => $oldQueue,
            'newQueue' => $newQueue
        ];
    }
    public function action(PlanQueue $oldQueue, PlanQueue $newQueue)
    {
        return URL::temporarySignedRoute('plan.queue.switch', now()->addMinutes(15), ['oldQueue' => $oldQueue, 'newQueue' => $newQueue]);
    }
}
