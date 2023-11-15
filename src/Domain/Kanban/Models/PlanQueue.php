<?php

namespace Domain\Kanban\Models;

use Domain\Users\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PlanQueue extends Model
{
    protected $connection = 'teresa_kanban';
    protected $table = 'queues';
    protected $fillable = [
        'queue_order',
        'plan_id',
        'status',
        'description'
    ];
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
