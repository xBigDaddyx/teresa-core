<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Sfolador\Locked\Traits\HasLocks;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Teresa\CartonBoxGuard\Models\CartonBox as ModelsCartonBox;
use Teresa\CartonBoxGuard\Traits\HasStringId;

class CartonBox extends ModelsCartonBox
{
    //use PowerJoins;
    //use \OwenIt\Auditing\Auditable;
    //use LogsActivity;
    use HasStringId;
    use HasLocks;
    use HasFactory;
    use BlameableTrait;
    use SoftDeletes;
    protected $primary = 'id';
    protected $connection = 'teresa_box';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = [];

    protected $guarded = [];

    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
    public function prefixable(): array
    {
        return [
            'id_prefix' => 'PB',
            'company_id' => Auth::user()->company->id,
            'company_short_name' => Auth::user()->company->short_name,
        ];
    }
    //protected $auditInclude = [
    //     'box_code', 'size', 'color', 'is_completed', 'carton_number', 'quantity', 'locked_at',
    // ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['box_code', 'size', 'color', 'is_completed', 'carton_number', 'quantity', 'locked_at'])
    //         ->logOnlyDirty();
    //     // Chain fluent methods for configuration options
    // }



    // protected $appends = [
    //     'percentage',

    // ];

    // public function getPercentageAttribute()
    // {
    //     if ($this->polybags->count() > 0) {
    //         return number_format($this->quantity == 0 ? 0 : ($this->polybags->count() / $this->quantity) * 100, 0);
    //     }

    //     return 0;
    // }

    // public function tags(): MorphMany
    // {
    //     return $this->morphMany(Tag::class, 'taggable');
    // }


    public function scopeOutstanding($query)
    {
        return $query->where('is_completed', false);
    }

    public function packingList(): BelongsTo
    {
        return $this->belongsTo(PackingList::class, 'packing_list_id', 'id');
    }

    public function packingLists(): BelongsTo
    {
        return $this->belongsTo(PackingList::class, 'packing_list_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function polybagTags(): HasManyThrough
    {
        return $this->hasManyThrough(Tag::class, Polybag::class, 'carton_box_id', 'taggable_id')->where(
            'taggable_type',
            Polybag::class
        );
    }

    public function cartonBoxAttributes(): HasMany
    {
        return $this->hasMany(CartonBoxAttribute::class);
    }

    public function updatedBy()
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'updated_by', 'id');
    }

    public function createdBy()
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by', 'id');
    }

    public function completedBy()
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'completed_by', 'id');
    }
}
