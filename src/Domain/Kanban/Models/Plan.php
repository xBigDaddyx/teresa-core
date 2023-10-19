<?php

namespace Domain\Kanban\Models;

use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Plan extends Model
{
    use BlameableTrait;
    use SoftDeletes;
    protected $connection = 'teresa_kanban';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
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
            $count = ($model::where('id', 'like', auth()->user()->company->short_name . '%')->withTrashed()->count() + 1);
            if ($count < 10) {
                $number = '00000' . $count;
            } elseif ($count >= 10 && $count < 100) {
                $number = '0000' . $count;
            } elseif ($count >= 100 && $count < 1000) {
                $number = '000' . $count;
            } elseif ($count >= 1000 && $count < 10000) {
                $number = '00' . $count;
            } elseif ($count >= 10000 && $count < 100000) {
                $number = '0' . $count;
            } else {
                $number = $count;
            }
            $model->company_id = auth()->user()->company->id;
            $model->id = auth()->user()->company->short_name . '.PLAN.' . $number;
        });
    }

    protected $fillable = [
        'id',
        'company_id',
        'sewing_id',
        'buyer',
        'contract_id',
        'style_id',
        'plan_qty',
        'sewing_start_date',
        'sewing_end_date',
        'exit_fty_date'
    ];
    public function sewing(): BelongsTo
    {
        return $this->belongsTo(Sewing::class, 'sewing_id', 'id');
    }
   
    // public function queue()
    // {
    //     return $this->hasOne(Queue::class, 'plan_id', 'id');
    // }
    public function company(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(Company::class, 'company_id', 'id');
    }
    // public function bundles(): HasMany
    // {
    //     return $this->hasMany(Bundle::class, 'plan_id', 'id');
    // }
    // public function packings(): HasMany
    // {
    //     return $this->hasMany(Packing::class, 'plan_id', 'id');
    // }
}
