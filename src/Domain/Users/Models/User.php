<?php

namespace Domain\Users\Models;

use Domain\Purchases\Models\ApprovalUser;
use Domain\Purchases\Models\Department;
use Domain\Purchases\Models\UserPurchaseDepartment;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Support\Traits\HasProfilePhoto;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Xbigdaddyx\HarmonyFlow\Traits\HasDesignations;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasTenants, LdapAuthenticatable
{
    use HasDesignations;
    use AuthenticatesWithLdap;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;
    public function canImpersonate()
    {
        return auth('ldap')->user()->hasRole('super-admin');
    }
    public function canBeImpersonated()
    {
        // Let's prevent impersonating other users at our own company
        return str_ends_with($this->email, '@hoplun.com') && $this->getRoleNames()->count() > 0;
    }
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@hoplun.com') && $this->hasRole('super-admin');
        } else if ($panel->getId() === 'accuracy') {
            return str_ends_with($this->email, '@hoplun.com') && $this->hasRole('packing-administration-officer') || $this->hasRole('super-admin');
        } else if ($panel->getId() === 'kanban') {
            return str_ends_with($this->email, '@hoplun.com') && $this->hasRole('kanban-officer') || $this->hasRole('super-admin');
        } else if ($panel->getId() === 'purchase') {
            return str_ends_with($this->email, '@hoplun.com') && $this->hasRole('purchase-user') || str_ends_with($this->email, '@hoplun.com') && $this->hasRole('purchase-officer') || str_ends_with($this->email, '@hoplun.com') && $this->hasRole('purchase-approver') || $this->hasRole('super-admin');
        }
        return true;
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_photo_url ? $this->profile_photo_url : null;
    }
    // public function getFilamentAvatarUrl(): ?string
    // {
    //     return $this->profilePhotoUrl;
    // }

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
    public function purchaseDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_purchase_department')->using(UserPurchaseDepartment::class)->withPivot(['company_id']);
    }
    public function approvalUser()
    {
        return $this->hasMany(ApprovalUser::class, 'user_id', 'id');
    }
}
