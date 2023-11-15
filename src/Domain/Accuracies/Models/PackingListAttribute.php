<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PackingListAttribute extends Model
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
            $count = ($model::where('id', 'like', Filament::getTenant()->short_name . '%')->withTrashed()->count() + 1);
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
<<<<<<< Updated upstream:src/Domain/Accuracies/Models/PackingListAttribute.php
            $model->type = $model->packingList->type;
            $model->id = Filament::getTenant()->short_name . '.PLA.' . $number;
=======
            $model->type = $model->carton->type;
            $model->id = Filament::getTenant()->short_name . '.CBA.' . $number;
>>>>>>> Stashed changes:src/Domain/Accuracies/Models/CartonBoxAttribute.php
        });
    }
    public function packingList(): BelongsTo
    {
        return $this->belongsTo(PackingList::class, 'packing_list_id', 'id');
    }
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'attributable');
    }
}
