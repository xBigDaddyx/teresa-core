<?php

namespace Domain\Purchases\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Domain\Users\Models\Company;

class State extends Model
{
    use SoftDeletes;
    use BlameableTrait;
    protected $connection = 'teresa_purchase';
    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];
    protected $guarded = [];
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $count = $model->whereBelongsTo(Filament::getTenant())->withTrashed()->count() + 1;
            $model->state_number = auth()->user()->company->short_name . '.ST.' . str_pad($count, 3, '0', STR_PAD_LEFT);
            $model->company_id = auth()->user()->company->id;
        });
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
