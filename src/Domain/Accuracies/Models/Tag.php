<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Tag extends Model
{
    use BlameableTrait;
    use SoftDeletes;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $connection = 'teresa_box';

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
            $count = ($model::where('id', 'like', auth()->user()->currentCompany->short_name . '%')->withTrashed()->count() + 1);

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
            $model->id = auth()->user()->currentCompany->short_name . '.TAG.' . $number;
        });
    }

    public function taggable()
    {
        return $this->morphTo();
    }

    public function attributable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by');
    }

    public function attribute()
    {
        return $this->belongsTo(PackingListAttribute::class, 'attribute_id', 'id');
    }

    public function updatedBy()
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'updated_by');
    }

    public function polybag(): BelongsTo
    {
        return $this->belongsTo(Polybag::class);
    }
}
