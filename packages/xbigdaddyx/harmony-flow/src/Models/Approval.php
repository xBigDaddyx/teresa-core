<?php

namespace Xbigdaddyx\HarmonyFlow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;
use Xbigdaddyx\HarmonyFlow\Contracts\ApprovalContract;
use Xbigdaddyx\HarmonyFlow\HarmonyFlow;
use Xbigdaddyx\HarmonyFlow\Jobs\CreateApprovalHistories;
use Xbigdaddyx\HarmonyFlow\Observers\ApprovalObserver;
use Xbigdaddyx\HarmonyFlow\Traits\HasHistory;
use Spatie\ModelStatus\HasStatuses;

class Approval extends Model implements ApprovalContract
{
    use HasStatuses;
    use HasHistory;
    protected $guarded = [];
    protected $casts = [
        'is_approved' => 'boolean',
        'is_rejected' => 'boolean',
        'is_completed' => 'boolean',

    ];
    protected static function booted()
    {
        static::updated(function ($model) {
            Log::info($model);
            if ($model->isDirty(['is_approved'])) {

                CreateApprovalHistories::dispatch($model, 'Approval request is approved', $model->chargeable_id, 'approve');
            } else if ($model->isDirty(['is_rejected'])) {

                CreateApprovalHistories::dispatch($model, 'Approval request is rejected', $model->chargeable_id, 'reject');
            }
        });
    }
    public function __construct()
    {
        $this->table = config('harmony-flow.table_names.approvals') ?: parent::getTable();
    }

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.companies'));
    }

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(config('harmony-flow.models.users'));
    // }
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.users'), 'chargeable_id', 'id');
    }
    public function hasActionTo($user): bool
    {

        return match ($user->id) {
            (int)$this->chargeable_id => true,
            !(int)$this->chargeable_id => false,
        };
    }
    public function flow(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.approval-flows'));
    }
}
