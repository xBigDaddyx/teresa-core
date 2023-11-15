<?php

namespace Domain\Kanban\Models;

use Domain\Users\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wise extends Model
{
    protected $connection = 'teresa_kanban';
    protected $keyType = 'string';
    protected $primaryKey = 'sewing_id';
    protected $fillable = [
        'sewing_id',
        'wip_qty',
        'bun_qty',
        'pac_qty',
        'plan_id',
        'status',
        'company_id',
        'sewing_line_type',
        'sewing_display_name',
        'is_blinked',
        'blinked_at'
    ];

    public function sewing(): BelongsTo
    {
        return $this->belongsTo(Sewing::class, 'sewing_id', 'id');
    }
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
    public function company(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(Company::class, 'company_id', 'id');
    }
}
