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

class UpdateApprovalRecord implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $action;
    protected $user;
    public function __construct($details, $action, $user)
    {
        $this->details = $details;
        $this->action = $action;
        $this->user = $user;
    }

    public function handle()
    {
        return match ($this->action) {
            'approve' => $this->approve(),
            'reject' => $this->reject(),
        };
    }
    public function approve()
    {
        $model = $this->details;
        $model->is_approved = true;
        $model->approved_at = now();
        $model->is_completed = true;
        $model->completed_at = now();
        $model->save();
    }
    public function reject()
    {
        $model = $this->details;
        $model->is_rejected = true;
        $model->rejected_at = now();
        $model->is_completed = true;
        $model->completed_at = now();
        $model->save();
    }
}
