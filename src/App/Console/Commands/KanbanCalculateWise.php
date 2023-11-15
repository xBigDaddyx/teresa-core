<?php

namespace App\Console\Commands;


use Domain\Kanban\Models\Bundle;
use Domain\Kanban\Models\Packing;
use Domain\Kanban\Models\PlanQueue;
use Domain\Kanban\Models\Wise;
use Domain\Kanban\Services\WiseStatusService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class KanbanCalculateWise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanban:calculate-wise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate sewing wise between BUN and PAC';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queues = PlanQueue::where('status', 'Active')->orWhere('status', 'Delayed')->with('plan', 'plan.sewing', 'plan.sewing.shifts')->get();
        $bar = $this->output->createProgressBar($queues->count());

        $bar->start();
        foreach ($queues as $queue) {
            $this->line(' Contract => ' . $queue->plan->contract_id);
            $wip_qty = 0;
            $bun_qty = new Collection();
            $pac_qty = new Collection();

            foreach ($queue->plan->sewing->shifts as $shift) {
                $logicalSewing = $queue->plan->sewing_id . '-' . $shift->name;
                $buns = Bundle::where('Contract_No', $queue->plan->contract_id)->where('Line_No', $logicalSewing)->get();
                $pacs = Packing::where('Contract_No', $queue->plan->contract_id)->where('Line_No', $logicalSewing)->get();
                $bun_qty->push(
                    $buns->sum('Received_QTY')
                );
                $pac_qty->push(
                    $pacs->sum('Received_QTY')
                );
            }

            $wip_qty = ($bun_qty->sum() - $pac_qty->sum());
            $statusService = new WiseStatusService($wip_qty, $queue->plan->sewing, 2);
            Wise::updateOrCreate(
                ['sewing_id' => $queue->plan->sewing_id, 'plan_id' => $queue->plan_id],
                [
                    'wip_qty' => $wip_qty,
                    'bun_qty' => $bun_qty->sum(),
                    'pac_qty' => $pac_qty->sum(),
                    'company_id' => $queue->plan->company_id,
                    'status' => $statusService->getStatus(),
                    'sewing_line_type' => $queue->plan->sewing->type,
                    'sewing_display_name' => $queue->plan->sewing->display_name,
                    'is_blinked' => $statusService->getBlink(),
                    'blinked_at' => $statusService->getBlinkedAt(),

                ]
            );
            $bar->advance();
            $this->newLine();
        }
        $bar->finish();
        $this->newLine();
    }
}
