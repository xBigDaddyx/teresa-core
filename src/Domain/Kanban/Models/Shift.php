<?php

namespace Domain\Kanban\Models;

use Domain\Users\Models\Company;
use Domain\Users\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Shift extends Model
{
    use BlameableTrait;
    use SoftDeletes;
    protected $connection = 'teresa_kanban';
    protected $fillable = [
        'id',
        'name',
        'description',
    ];
    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];
    public function sewings(): BelongsToMany
    {
        return $this->belongsToMany(Sewing::class, 'shift_sewing');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
