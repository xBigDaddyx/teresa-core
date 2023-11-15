<?php

namespace Teresa\CartonBoxGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Teresa\CartonBoxGuard\Events\PolybagCreated;
use Teresa\CartonBoxGuard\Traits\HasStringId;

class Polybag extends Model
{
    use HasStringId;
    use SoftDeletes;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = [];

    protected $guarded = [];

    public function prefixable(): array
    {
        return [
            'id_prefix' => 'PB',
            'company_id' => Auth::user()->company->id,
            'company_short_name' => Auth::user()->company->short_name,
        ];
    }

    protected $dispatchesEvents = [

        'created' => \Teresa\CartonBoxGuard\Events\PolybagCreated::class,
        //..
    ];

    public function __construct(array $attributes = [])
    {
        if (!isset($this->connection)) {
            $this->setConnection(config('carton-box-guard.database_connection'));
        }

        if (!isset($this->table)) {
            $this->setTable(config('carton-box-guard.polybag.table_name'));
        }

        parent::__construct($attributes);
    }
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }

    public function cartonBox(): BelongsTo
    {
        return $this->belongsTo(CartonBox::class);
    }
}
