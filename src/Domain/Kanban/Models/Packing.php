<?php

namespace Domain\Kanban\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Packing extends Model
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
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class, 'Bundle_No', 'Bundle_No');
    }
    public function sewing(): BelongsTo
    {
        return $this->belongsTo(Sewing::class, 'sewing_id', 'id');
    }
}
