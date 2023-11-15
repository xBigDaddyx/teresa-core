<?php

namespace Domain\Purchases\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
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
            $model->product_number = auth()->user()->company->short_name . '.PD.' . str_pad($count, 7, '0', STR_PAD_LEFT);
            $model->company_id = auth()->user()->company->id;
        });
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    protected $casts = [
        'specification' => 'array'
    ];
    public function createdBy(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'updated_by', 'id');
    }
}
