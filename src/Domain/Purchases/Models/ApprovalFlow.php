<?php

namespace Domain\Purchases\Models;

use Domain\Users\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class ApprovalFlow extends Model
{
    use SoftDeletes;
    use BlameableTrait;
    protected $guarded = [];
    protected $casts = [
        'parameter' => 'array',
        'is_skipable' => 'boolean',
        'is_last_stage' => 'boolean'
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {

            $model->company_id = auth()->user()->company->id;
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function requests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'approval_flow_id', 'id');
    }
}
