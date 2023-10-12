<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Buyer extends Model
{
    //use \OwenIt\Auditing\Auditable;
    //use LogsActivity;
    use HasFactory;
    use BlameableTrait;
    use SoftDeletes;

    protected $connection = 'teresa_box';

    protected $fillable = [];

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $guarded = [];

    //protected $auditTimestamps = true;

    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];

    // protected $auditEvents = [
    //     'deleted',
    //     'restored',
    //     'updated',
    // ];

    // protected $auditInclude = [
    //     'name',
    //     'country',
    // ];

    public static function boot()
    {

        parent::boot();
        self::creating(function ($model) {
            $count = ($model::where('id', 'like', auth()->user()->company->short_name . '%')->withTrashed()->count() + 1);

            if ($count < 10) {
                $number = '00' . $count;
            } elseif ($count >= 10 && $count < 100) {
                $number = '0' . $count;
            } else {
                $number = $count;
            }
            $model->company_id = auth()->user()->company->id;
            $model->id = auth()->user()->company->short_name . '.BY.' . $number;
        });
    }

    public function lists(): HasMany
    {
        return $this->hasMany(PackingList::class, 'buyer_id', 'id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['name', 'country', 'deleted_at'])
    //         ->logOnlyDirty();
    //     // Chain fluent methods for configuration options
    // }
}
