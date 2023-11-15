<?php

namespace Domain\Purchases\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RequestItem extends Pivot
{
    protected $connection = 'teresa_purchase';
    protected $fillable = [
        'request_id',
        'product_id',
        'contract_no',
        'quantity',
        'delivery_date',
        'remark',
        'stock',
        'style_no',
        'company_id',
    ];
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
