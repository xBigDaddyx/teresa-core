<?php

namespace Teresa\CartonBoxGuard\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Sfolador\Locked\Traits\HasLocks;
use Teresa\CartonBoxGuard\Traits\HasStringId;

class CartonBox extends Model
{
    use HasLocks;
    use HasStringId;
    use SoftDeletes;

    protected $primary = 'id';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public function prefixable(): array
    {
        return [
            'id_prefix' => 'CB',
            'company_id' => Auth::user()->company->id,
            'company_short_name' => Auth::user()->company->short_name,
        ];
    }

    public function __construct(array $attributes = [])
    {
        if (!isset($this->connection)) {
            $this->setConnection(config('carton-box-guard.database_connection'));
        }

        if (!isset($this->table)) {
            $this->setTable(config('carton-box-guard.carton.table_name'));
        }

        parent::__construct($attributes);
    }

    protected $guarded = [];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
    public function cartonBoxAttributes(): HasMany
    {
        return $this->hasMany(CartonBoxAttribute::class);
    }

    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }
    public function polybags(): HasMany
    {
        return $this->hasMany(Polybag::class);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query
            ->where('is_completed', true);
    }
}
