<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Teresa\CartonBoxGuard\Models\Polybag as ModelsPolybag;
use Teresa\CartonBoxGuard\Traits\HasStringId;

class Polybag extends ModelsPolybag
{
    use HasStringId;
    use HasFactory;
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

    public function prefixable(): array
    {
        return [
            'id_prefix' => 'PB',
            'company_id' => Auth::user()->company->id,
            'company_short_name' => Auth::user()->company->short_name,
        ];
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
