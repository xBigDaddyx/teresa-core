<?php

namespace Xbigdaddyx\HarmonyFlow\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ApprovalContract
{
    public function hasActionTo($user): bool;
}
