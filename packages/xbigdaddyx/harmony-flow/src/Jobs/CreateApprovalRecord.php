<?php

namespace Xbigdaddyx\HarmonyFlow\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CreateApprovalRecord implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $type;
    protected $requester;
    public function __construct($details, $type, $requester)
    {
        $this->details = $details;
        $this->type = $type;
        $this->requester = $requester;
    }
    public function resolveModel(string $modelName)
    {
        $config = config('harmony-flow.models.' . $modelName);

        if ($config) {
            return app($config);
        }

        // Atau lempar exception jika model tidak ditemukan
        throw new \Exception("Model '$modelName' not found in configuration.");
    }
    public function handle()
    {

        $details = $this->details;

        //get flow
        $flow = $this->resolveModel('approval-flows')->where('type', $this->type)->where('order', 1)->first();
        $charge = $this->resolveModel('users')->whereHas('designations', function (Builder $query) use ($flow, $details) {
            $query->where('name', $flow->designation->name)->where('department_id', $details->department_id);
        })->first();

        $model = $this->resolveModel('approvals');
        $model->type = $this->type;
        $model->approvable_id = $details->id;
        $model->flow_id = $flow->id;
        $model->approvable_type = get_class($details);
        $model->chargeable_id = $charge->id;
        $model->chargeable_type = get_class($charge);
        $model->created_by = $this->requester->id;
        $model->updated_by = $this->requester->id;
        $model->company_id = $this->requester->company->id;
        $model->department_id = $details->department_id;
        $model->save();

        $model->setStatus('pending', 'Waiting response from ' . $charge->name);
    }
    public function failed(Throwable $exception)
    {
        Log::error($exception);
    }
}
