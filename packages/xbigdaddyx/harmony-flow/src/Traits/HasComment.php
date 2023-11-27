<?php

namespace Xbigdaddyx\HarmonyFlow\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasComment
{
    public function comments()
    {
        return $this->morphMany(config('harmony-flow.models.comments'), 'commentable');
    }

    public function getCommentsByDepartment()
    {
        $model = $this;
        return (config('harmony-flow.models.comments'))::with('user.departments')->whereHas('user.departments', function (Builder $query) use ($model) {
            $query->where('department_id', $model->department_id);
        })->where('commentable_id', $model->id);
    }
}
