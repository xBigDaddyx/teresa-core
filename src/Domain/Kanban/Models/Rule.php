<?php

namespace Domain\Kanban\Models;

use Domain\Accuracies\Models\Buyer;
use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Rule extends Model
{
    use BlameableTrait;
    use SoftDeletes;
    protected $connection = 'teresa_kanban';
    protected $table = 'stock_rules';
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
            $model->id = auth()->user()->company->short_name . '.RULE.' . $number;
        });
    }
    protected $fillable = [
        'id',
        'name',
        'level',
        'sewing_type',
        'value',
        'unit',
        'company_id'
    ];
    public function sewing(): BelongsTo
    {
        return $this->belongsTo(Sewing::class, 'sewing_id', 'id');
    }
    public function customer(): BelongsTo
    {
        return $this->setConnection('teresa_box')->belongsTo(Buyer::class, 'buyer', 'id');
    }
    // public function queue()
    // {
    //     return $this->hasOne(Queue::class, 'plan_id', 'id');
    // }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
