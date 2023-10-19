<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class CartonBoxAttribute extends Model
{
    use BlameableTrait;
    use SoftDeletes;

    protected $connection = 'teresa_box';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

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
                $number = '0000' . $count;
            } elseif ($count >= 10 && $count < 100) {
                $number = '000' . $count;
            } elseif ($count >= 100 && $count < 1000) {
                $number = '00' . $count;
            } elseif ($count >= 1000 && $count < 10000) {
                $number = '0' . $count;
            } else {
                $number = $count;
            }
            $model->type = $model->carton->type;
            $model->id = auth()->user()->company->short_name . '.CBA.' . $number;
        });
    }
    public function carton(): BelongsTo
    {
        return $this->belongsTo(CartonBox::class, 'carton_box_id', 'id');
    }
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'attributable');
    }

    public function packingList(): BelongsTo
    {
        return $this->belongsTo(CartonBox::class);
    }
}
