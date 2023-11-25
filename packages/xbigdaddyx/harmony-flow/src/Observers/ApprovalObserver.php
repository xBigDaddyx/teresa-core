<?php

namespace Xbigdaddyx\HarmonyFlow\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Xbigdaddyx\HarmonyFlow\HarmonyFlow;
use Xbigdaddyx\HarmonyFlow\Jobs\CreateApprovalHistories;

class ApprovalObserver
{
    public function updated(Model $model): void
    {
        if ($model->isDirty('is_approved')) {

            dispatch(new CreateApprovalHistories($model, 'Approval request is approved', $model->chargeable_id, 'approve'));
        } else if ($model->isDirty('is_rejected')) {

            dispatch(new CreateApprovalHistories($model, 'Approval request is rejected', $model->chargeable_id, 'reject'));
        }
    }
}
