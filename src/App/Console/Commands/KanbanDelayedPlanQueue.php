<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Domain\Kanban\Models\PlanQueue;
use Illuminate\Console\Command;

class KanbanDelayedPlanQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanban:check-delayed';

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
        $queues = PlanQueue::all();

        foreach ($queues as $que) {
            $end_date = Carbon::parse($que->plan->sewing_end_date);
            $now = Carbon::now();
            if ($now->gt($end_date)) {
                if ($que->status === 'Active' && $que->status !== 'Completed') {
                    $this->line($que->plan->contract_id);
                    $que->status = 'Delayed';
                    $que->delayed_at = Carbon::now();
                    $que->save();
                } else if ($que->status === 'Delayed' && $que->status !== 'Completed') {
                    // if ($que->status !== 'Delayed') {
                    //     // foreach ($users as $user) {
                    //     //     Notification::make()
                    //     //         ->success()
                    //     //         ->icon('tabler-file-x')
                    //     //         ->title('The queues is delayed')
                    //     //         ->body('Line ' . $que->plan->sewing_id . ' ' . $que->plan->contract_id . ' is delayed until now')
                    //     //         // ->actions([
                    //     //         //     Action::make('view')
                    //     //         //         ->color('primary')
                    //     //         //         ->button()
                    //     //         //         ->url(route('filament.resources.carton-boxes.view', $this->carton_box->id), shouldOpenInNewTab: true),
                    //     //         // ])
                    //     //         ->sendToDatabase(User::where('email', $user)->first());
                    //     // }
                    // }
                }
                $que->delayed_at = Carbon::now();
                $que->save();
            }
        }
    }
}
