<?php

namespace Domain\Purchases\Models;

use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Category extends Model
{
    use HasFactory;
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
            $model->category_number = auth()->user()->company->short_name . '.CG.' . str_pad($count, 2, '0', STR_PAD_LEFT);
            $model->company_id = auth()->user()->company->id;
        });
    }
    public function createdBy(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'updated_by', 'id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
