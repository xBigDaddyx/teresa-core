<?php

namespace Domain\Purchases\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Filament\Facades\Filament;

class ProductCategory extends Model
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function createdBy(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'updated_by', 'id');
    }
}
