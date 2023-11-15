<?php

namespace Domain\Purchases\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Domain\Users\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserPurchaseDepartment extends Pivot
{
    protected $fillable = [
        'user_id',
        'department_id',
        'company_id',
    ];
    protected $guarded = [];
    // public function user(): BelongsTo
    // {
    //     return $this->setConnection('sqlsrv')->belongsTo(User::class);
    // }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
