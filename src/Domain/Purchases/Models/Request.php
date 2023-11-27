<?php

namespace Domain\Purchases\Models;

use Carbon\Carbon;
use Domain\Users\Models\Company;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
// use Domain\Purchases\Traits\HasApproval;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Xbigdaddyx\HarmonyFlow\Traits\HasApproval;
use Spatie\ModelStatus\HasStatuses;
use Xbigdaddyx\HarmonyFlow\Models\ApprovableModel;

class Request extends ApprovableModel
{
    // use HasStatuses;
    // use HasApproval;

    use SoftDeletes;
    use BlameableTrait;

    protected static $blameable = [
        'guard' => null,
        'user' => User::class,
        'createdBy' => 'created_by',
        'updatedBy' => 'updated_by',
    ];
    protected $guarded = [];
    public static function boot()
    {
        parent::boot();
        // static::bootHasApproval();
        self::creating(function ($model) {



            $count = $model->whereBelongsTo(Filament::getTenant())->withTrashed()->count() + 1;
            $model->request_number = 'PR' . auth()->user()->purchaseDepartments()->first()->short_name . '-' . str_pad($count, 5, '0', STR_PAD_LEFT) . '/' . $model->convertToRoman((int)Carbon::now()->format('m')) . '/' . (int)Carbon::now()->format('Y');
            $model->company_id = auth()->user()->company->id;
            $model->department_id = auth()->user()->purchaseDepartments()->first()->id;
        });
    }
    function convertToRoman($integer)
    {
        // Convert the integer into an integer (just to make sure)
        $integer = intval($integer);
        $result = '';

        // Create a lookup array that contains all of the Roman numerals.
        $lookup = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        foreach ($lookup as $roman => $value) {
            // Determine the number of matches
            $matches = intval($integer / $value);

            // Add the same number of characters to the string
            $result .= str_repeat($roman, $matches);

            // Set the integer to be the remainder of the integer and the value
            $integer = $integer % $value;
        }

        // The Roman numeral should be built, return it
        return $result;
    }
    public function company(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(Company::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function requestItems(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }
    public function department(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(Department::class);
    }
    protected $casts = [
        'is_submited' => 'boolean',
        'is_processed' => 'boolean',
    ];
    public function createdBy(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'created_by', 'id');
    }
    // public function approvals(): MorphMany
    // {
    //     return $this->setConnection('sqlsrv')->morphMany(ApprovalRequest::class, 'approvable');
    // }
    public function approvalHistories(): MorphMany
    {
        return $this->setConnection('sqlsrv')->morphMany(ApprovalHistory::class, 'approvable');
    }
    public function person(): BelongsTo
    {
        return $this->setConnection('sqlsrv')->belongsTo(User::class, 'user_id', 'id');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
