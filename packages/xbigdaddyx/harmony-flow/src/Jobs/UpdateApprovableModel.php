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

class UpdateApprovableModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $status;
    protected $processor;
    public function __construct($details, $status, $processor = null)
    {
        $this->details = $details;
        $this->status = $status;
        if (isset($processor)) {
            $this->processor = $processor;
        }
    }

    public function handle()
    {
        return match ($this->status) {
            'submit' => $this->submit(),
            'process' => $this->process(),
        };
    }
    public function process()
    {
        $model = $this->details;
        $model->is_processed = true;
        $model->processed_by = $this->processor;
        $model->save();
    }
    public function submit()
    {
        $model = $this->details;
        $model->is_submited = true;
        $model->save();
    }
}
