<?php

namespace Xbigdaddyx\HarmonyFlow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Xbigdaddyx\HarmonyFlow\ApprovalRegistrar;
use Xbigdaddyx\HarmonyFlow\Contracts\FlowContract;
use Xbigdaddyx\HarmonyFlow\HarmonyFlow;

class Flow extends Model implements FlowContract
{
    protected $guarded = [];
    protected $fillable = [
        'type',
        'order',
        'designation_id',
        'parameter',
        'company_id',
        'is_skipable',
        'is_last',
    ];
    protected $casts = [
        'is_skipable' => 'boolean',
        'parameter' => 'array',
        'is_last' => 'boolean',
    ];
    public function __construct()
    {
        $this->table = config('harmony-flow.table_names.flows') ?: parent::getTable();
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(config('harmony-info.models.approvals'), app(HarmonyFlow::class)->pivotFlow, 'id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.companies'));
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.users'));
    }
    public function designation(): BelongsTo
    {
        return $this->belongsTo(config('harmony-flow.models.designations'));
    }
}
