<?php

namespace Domain\Kanban\Models;

use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Sewing extends Model
{
    use BlameableTrait;
    use SoftDeletes;
    protected $connection = 'teresa_kanban';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'type',
        'company_id',
        'display_name'
    ];
    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];
    public function shifts(): BelongsToMany
    {
        return $this->belongsToMany(Shift::class, 'shift_sewing');
    }
    public function company(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(Company::class, 'company_id', 'id');
    }
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class, 'sewing_id', 'id');
    }
}
