<?php

namespace App\Jobs;

use App\Notifications\PlanQueueNotification;
use Domain\Kanban\Models\PlanQueue;
use Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SwitchPlanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $plan_queue;
    /**
     * Create a new job instance.
     */
    public function __construct(PlanQueue $plan_queue)
    {
        $this->plan_queue = $plan_queue;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $plan_queue = $this->plan_queue;
        $queues = PlanQueue::whereHas('plan', function (Builder $query) use ($plan_queue) {
            $query->where('sewing_id', $plan_queue->plan->sewing_id);
        })->where('queue_order', '>', $plan_queue->queue_order)->orderBy('queue_order', 'ASC')->get();
        if ($queues->count() > 0) {
            $next_queue = $queues->where('queue_order', '=', $plan_queue->queue_order + 1)->first();
            $plan_queue->status = 'Completed';
            if ($plan_queue->save()) {
                $next_queue->status = 'Active';
                if ($next_queue->save()) {
                    $user = User::where('email', 'adi.purwanto@hoplun.com')->orWhere('email', 'faisal.yusuf@hoplun.com')->get();
                    $mail_content = [
                        'first_line' => "Please check, this plan queue for " . $plan_queue->plan->sewing_id,
                        'second_line' => 'With plan contract ' . $plan_queue->plan->contract_id . ' style ' . $plan_queue->plan->style_id . ' quantity ' . $plan_queue->plan->plan_qty . ' is completed.',
                        'url_action' => '/kanban/IDIS/plan-queues',
                    ];

                    Notification::send($user, new PlanQueueNotification($mail_content));
                }
            }
        }
    }
}
