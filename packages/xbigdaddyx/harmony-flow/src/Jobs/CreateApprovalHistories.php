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

class CreateApprovalHistories implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $subject;
    protected $user;
    protected $action;
    public function __construct($details, $subject, $user, $action)
    {
        $this->details = $details;
        $this->subject = $subject;
        $this->user = $user;
        $this->action = $action;
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
        $model = app(config('harmony-flow.models.histories'));
        $model->historable_id = $this->details->id;
        $model->historable_type = get_class($this->details);
        $model->subject = $this->subject;
        $model->action = $this->action;
        $model->user_id = $this->user;
        return $model->save();
    }
}
