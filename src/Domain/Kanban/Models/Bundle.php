<?php

namespace Domain\Kanban\Models;



use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bundle extends Model
{
    protected $connection = 'teresa_kanban';
    protected $fillable = [
        'Country',
        'Factory_Code',
        'Contract_No',
        'MO_No',
        'Style_No',
        'Job_Order',
        'Color',
        'Bundle_No',
        'Received_QTY',
        'WIP_CheckPoint',
        'Line_No',
        'Create_Date',
        'plan_id',
        'sewing_id',
        'status',
    ];

    // public function getStatusAttribute(): string
    // {
    //     $statusService = new CheckStatusBundleService();
    //     return $statusService->status($this->Bundle_No, $this->Line_No);
    // }
    // public function scopeInvalid($query)
    // {
    //     return $query->whereHas('packing', function (Builder $packing) {
    //         $packing->whereNotNull('sewing_id')->where('sewing_id', '!=', $this->sewing_id);
    //     });
    // }
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
    public function packing(): BelongsTo
    {
        return $this->belongsTo(Packing::class, 'Bundle_No', 'Bundle_No');
    }
    public function sewing(): BelongsTo
    {
        return $this->belongsTo(Sewing::class, 'sewing_id', 'id');
    }
}
