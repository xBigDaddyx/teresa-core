<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Polybag extends Model
{
    use HasFactory;
    use BlameableTrait;
    use SoftDeletes;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $connection = 'teresa_box';

    protected $fillable = [];

    protected $guarded = [];

    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $count = ($model::where('id', 'like', auth()->user()->company->short_name . '%')->withTrashed()->count() + 1);

            if ($count < 10) {
                $number = '000000' . $count;
            } elseif ($count >= 10 && $count < 100) {
                $number = '00000' . $count;
            } elseif ($count >= 100 && $count < 1000) {
                $number = '0000' . $count;
            } elseif ($count >= 1000 && $count < 10000) {
                $number = '000' . $count;
            } elseif ($count >= 10000 && $count < 100000) {
                $number = '00' . $count;
            } elseif ($count >= 100000 && $count < 1000000) {
                $number = '0' . $count;
            } else {
                $number = $count;
            }
            $model->company_id = auth()->user()->company->id;
            $model->id = auth()->user()->company->short_name . '.PB.' . $number;
        });
    }

    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }

    public function user()
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by', 'id');
    }

    public function createdBy()
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by', 'id');
    }

    public function box()
    {
        return $this->belongsTo(CartonBox::class, 'carton_box_id', 'id');
    }

    // public function garments()
    // {
    //     return $this->hasMany(PolybagGarment::class);
    // }

    // public function polybagGarments(): BelongsToMany
    // {
    //     return $this->belongsToMany(CartonBoxAttribute::class, 'polybag_garments');
    // }

    // protected static function newFactory()
    // {
    //     return \Modules\Packing\Database\factories\PolybagFactory::new();
    // }
}
