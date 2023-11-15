<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Domain\Kanban\Models\PlanQueue;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class KanbanOngoingPlanQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanban:check-ongoing-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queues = PlanQueue::where('status', 'ongoing')->get();
        foreach ($queues as $q) {
            $sameQueue = PlanQueue::whereHas('plan', function (Builder $query) use ($q) {
                $query->where('sewing_id', $q->plan->sewing_id);
            })->first();
            if ($sameQueue->queue_order < $q->queue_order) {
                $old_end = Carbon::parse($sameQueue->plan->sewing_end_date);
                $new_start = Carbon::parse($q->plan->sewing_start_date);
                $difference = $new_start->diffInDays($old_end, false);
                if ($old_end->gt($new_start)) {
                    if ($difference <= 0) {
                        //send notification nearly
                    }
                    //send confirmation to switch
                }
            }
        }
    }
}
