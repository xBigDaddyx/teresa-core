<?php

namespace Domain\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'short_name',
        'logo',
        'user_id',
    ];

    protected $casts = [
        'personal_company' => 'boolean',
    ];

    public static function boot()
    {

        parent::boot();
        self::creating(function ($model) {
            $model->personal_company = false;
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'current_company_id', 'id');
    }
}
