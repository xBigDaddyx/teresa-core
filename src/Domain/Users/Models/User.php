<?php

namespace Domain\Users\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Support\Traits\HasProfilePhoto;


class User extends Authenticatable implements FilamentUser, HasAvatar, HasTenants
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;


    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@hoplun.com') && $this->hasRole('super-admin');
        } else if ($panel->getId() === 'accuracy') {
            return str_ends_with($this->email, '@hoplun.com') && $this->hasRole('packing-administration-officer') || $this->hasRole('super-admin');
        } else if ($panel->getId() === 'kanban') {
            return str_ends_with($this->email, '@hoplun.com') && $this->hasRole('kanban-officer') || $this->hasRole('super-admin');
        }
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profilePhotoUrl;
    }

    public function getAvatarAttribute()
    {
        return filament()->getUserAvatarUrl($this);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'social_id',
        'social_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // /**
    //  * The accessors to append to the model's array form.
    //  *
    //  * @var array<int, string>
    //  */
    protected $appends = [
        'profile_photo_url',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'current_company_id', 'id');
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->companies;
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->companies->contains($tenant);
    }
}
