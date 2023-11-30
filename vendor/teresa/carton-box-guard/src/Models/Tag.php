<?php

namespace Teresa\CartonBoxGuard\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Teresa\CartonBoxGuard\Traits\HasStringId;

class Tag extends Model
{
    use SoftDeletes;
    // use HasStringId;
    // protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $guarded = [];
    // public function prefixable(): array
    // {
    //     return [
    //         'id_prefix' => 'TAG',
    //         'company_id' => Auth::user()->company->id,
    //         'company_short_name' => Auth::user()->company->short_name,
    //     ];
    // }
    public function __construct(array $attributes = [])
    {
        if (!isset($this->connection)) {
            $this->setConnection(config('carton-box-guard.database_connection'));
        }

        if (!isset($this->table)) {
            $this->setTable(config('carton-box-guard.tag.table_name'));
        }

        parent::__construct($attributes);
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
