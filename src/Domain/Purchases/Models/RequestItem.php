<?php

namespace Domain\Purchases\Models;

use Domain\Users\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RequestItem extends Pivot
{

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
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
