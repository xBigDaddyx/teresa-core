<?php

namespace Xbigdaddyx\HarmonyFlow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Xbigdaddyx\HarmonyFlow\Contracts\HistoryContract;

class History extends Model implements HistoryContract
{
    protected $guarded = [];
    public function __construct()
    {
        $this->table = config('harmony-flow.table_names.histories') ?: parent::getTable();
    }
    public function historable(): MorphTo
    {
        return $this->morphTo();
    }
}
