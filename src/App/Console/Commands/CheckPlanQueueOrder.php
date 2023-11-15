<?php

namespace App\Console\Commands;

use App\Mail\SendPlanQueueNotification;
use App\Notifications\PlanQueueNotification;
use Carbon\Carbon;
use Domain\Kanban\Models\PlanQueue;
use Domain\Users\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CheckPlanQueueOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanban:check-queue-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check plan queue order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plans = PlanQueue::with('plan')->get();
        foreach ($plans as $queue) {
            $end_date =  Carbon::createFromFormat('Y-m-d', $queue->plan->sewing_end_date);
            $now = Carbon::now();
            $difference = $now->diffInDays($end_date, false);
            if ($difference > 0) {
                $user = User::find(381);
                Mail::to($user)->send(
                    new SendPlanQueueNotification($user, PlanQueue::find($queue->id))
                );
            }
        }
    }
}
