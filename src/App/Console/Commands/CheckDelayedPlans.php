<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Domain\Kanban\Models\Plan;
use Domain\Kanban\Models\PlanQueue;
use Illuminate\Console\Command;

class CheckDelayedPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:check-delayed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check delayed plans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->activePlan();
        $this->delayPlan();
    }
    public function delayPlan()
    {
        $plans = PlanQueue::with('plan')->where('status', 'Delayed')->get();
        foreach ($plans as $queue) {
            $end_date =  Carbon::createFromFormat('Y-m-d', $queue->plan->sewing_end_date);
            $now = Carbon::now();
            $difference = $now->gt($end_date);
            if ($difference) {
                $queue->update(['delayed_at', $now]);
            }
        }
    }
    public function activePlan()
    {
        $plans = PlanQueue::with('plan')->where('status', 'Active')->get();
        foreach ($plans as $queue) {
            $end_date =  Carbon::createFromFormat('Y-m-d', $queue->plan->sewing_end_date);
            $now = Carbon::now();
            $difference = $now->gt($end_date);
            if ($difference) {
                $queue->update(['status' => 'Delayed', 'delayed_at', $now]);
            }
        }
    }
}
