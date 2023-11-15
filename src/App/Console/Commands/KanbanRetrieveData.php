<?php

namespace App\Console\Commands;


use Domain\Kanban\Models\PlanQueue;
use Domain\Kanban\Models\Shift;
use Domain\Kanban\Services\StoreCheckpointService;
use Domain\Users\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class KanbanRetrieveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanban:retrieve-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve data from AIO';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companies = Company::with('sewings')->get();

        foreach ($companies as $company) {
            //======= Check Sewings each factory not null ========//
            if ($company->sewings->count() > 0) {
                $shifts = Shift::all();

                $queues = PlanQueue::where('status', 'Active')->orWhere('status', 'Delayed')->with('plan')->get();
                if ($queues->count() > 0) {
                    $bar = $this->output->createProgressBar($queues->count());

                    $bar->start();
                    foreach ($queues as $queue) {

                        $this->line(' Contract => ' . $queue->plan->contract_id);
                        $request = Http::get('http://10.20.200.52/restapi/aio/production/BundleScanInfo?Country=INDONESIA&Factory_Code=' . $company->short_name . '&Line_No=&MO_No=&Contract_No=' . $queue->plan->contract_id);
                        $collection = $request->collect('RESULT');
                        $BUN = new Collection($collection->where('WIP_CheckPoint', '=', $company->short_name . 'BUN'));
                        $PAC = new Collection($collection->where('WIP_CheckPoint', '=', $company->short_name . 'PAC'));
                        $storeData = new StoreCheckpointService();
                        if ($BUN->count() > 0) {

                            $storeData->store('BUN', $BUN, $queue->plan_id);
                        }
                        if ($PAC->count() > 0) {
                            $storeData->store('PAC', $PAC, $queue->plan_id);
                        }
                        $bar->advance();
                        $this->newLine();
                    }
                    $bar->finish();
                    $this->newLine();
                }
            }
        }

        return Command::SUCCESS;
    }
}
