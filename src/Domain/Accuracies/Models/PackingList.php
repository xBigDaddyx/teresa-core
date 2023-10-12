<?php

namespace Domain\Accuracies\Models;

use Domain\Users\Models\Company;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use RichanFongdasen\EloquentBlameable\BlameableTrait;
//use OwenIt\Auditing\Contracts\Auditable;

//class PackingList extends Model implements Auditable
class PackingList extends Model
{
    //use \OwenIt\Auditing\Auditable;
    use HasFactory;

    use BlameableTrait;
    use SoftDeletes;

    // protected $auditInclude = [
    //     'id', 'buyer_id', 'po', 'style_no', 'contract_no', 'batch', 'description', 'is_ratio', '',
    // ];
    protected $connection = 'teresa_box';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'is_ratio' => 'boolean',
    ];

    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];

    // protected $appends = [
    //     'percentage',
    //     'completedBoxCount',
    // ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $count = ($model::where('id', 'like', auth()->user()->currentCompany->short_name . '%')->withTrashed()->count() + 1);
            if ($count < 10) {
                $number = '000' . $count;
            } elseif ($count >= 10 && $count < 100) {
                $number = '00' . $count;
            } elseif ($count >= 100 && $count < 1000) {
                $number = '0' . $count;
            } else {
                $number = $count;
            }
            $model->company_id = auth()->user()->currentCompany->id;
            $model->id = auth()->user()->currentCompany->short_name . '.PL.' . $number;
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    // public function getCompletedBoxCountAttribute()
    // {
    //     return $completed = $this->cartonBoxes->where('is_completed', true)->count();
    // }

    // public function getPercentageAttribute()
    // {
    //     if ($this->with('cartonBoxes')->whereRelation('cartonBoxes', 'is_completed', '=', false)->count() > 0) {
    //         $outstanding = $this->cartonBoxes->where('is_completed', false)->count();
    //         $completed = $this->cartonBoxes->where('is_completed', true)->count();
    //         $total_cartonBoxes = $this->cartonBoxes->count();

    //         return number_format($total_cartonBoxes == 0 ? 0 : ($completed / $total_cartonBoxes) * 100, 2);
    //         //return number_format(($this->polybags->count() / $this->quantity) * 100, 1);
    //         //return $this->with('boxes')->whereRelation('boxes', 'is_completed', '=', true)->count();
    //     }

    //     return 0;
    // }

    // public function buyers()
    // {
    //     return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    // }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }

    public function cartonBoxes()
    {
        return $this->hasMany(CartonBox::class, 'packing_list_id', 'id');
    }

    // public function packingListAttributes(): HasMany
    // {
    //     return $this->hasMany(PackingListAttribute::class);
    // }
}
