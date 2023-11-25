<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Log;
use ReflectionClass;
use Xbigdaddyx\HarmonyFlow\Jobs\CreateApprovalHistories;
use Illuminate\Support\Collection;

trait HasHistory
{

    public static function bootHasHistory()
    {
        static::updated(function (Model $model) {
            Log::info($model);
            if ($model->isDirty(['is_approved'])) {

                CreateApprovalHistories::dispatch($model, 'Approval request is approved', $model->chargeable_id, 'approve');
            } else if ($model->isDirty(['is_rejected'])) {

                CreateApprovalHistories::dispatch($model, 'Approval request is rejected', $model->chargeable_id, 'reject');
            }
        });
    }

    public function approvalHistory(): MorphMany
    {
        return $this->morphMany(config('harmony-flow.models.histories'), 'historable');
    }
}
