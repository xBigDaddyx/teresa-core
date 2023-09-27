<?php

namespace Domain\Users\Models;

use Domain\Accuracies\Models\PackingList;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Support\Traits\HasProfileLogo;

class Company extends Model implements HasAvatar, HasCurrentTenantLabel
{
    use HasFactory, HasProfileLogo;

    public function getCurrentTenantLabel(): string
    {
        return 'Active company';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->logo_url;
    }

    protected $guarded = [
        'id',
    ];

    public function getFilamentName(): string
    {
        return "{$this->name} ({$this->short_name})";
    }

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
            $model->created_by = auth()->user()->id;
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'current_company_id', 'id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user');
    }

    public function packingLists(): HasMany
    {
        return $this->hasMany(PackingList::class, 'company_id', 'id');
    }

    protected $appends = [
        'logo_url',
    ];
}
