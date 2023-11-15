<?php

namespace Jeffgreco13\FilamentBreezy;

use Closure;
use Filament\Forms;
use Filament\Panel;
use Livewire\Livewire;
use BaconQrCode\Writer;
use Illuminate\Support\Arr;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Illuminate\Cache\Repository;
use Filament\Navigation\MenuItem;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\Color\Rgb;
use Jeffgreco13\FilamentBreezy\Pages;
use BaconQrCode\Renderer\ImageRenderer;
use Illuminate\Validation\Rules\Password;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use Filament\Support\Concerns\EvaluatesClosures;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Contracts\Auth\Authenticatable;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;
use Jeffgreco13\FilamentBreezy\Livewire\SanctumTokens;
use Jeffgreco13\FilamentBreezy\Livewire\UpdatePassword;
use Jeffgreco13\FilamentBreezy\Middleware\MustTwoFactor;
use Jeffgreco13\FilamentBreezy\Livewire\TwoFactorAuthentication;
use Jeffgreco13\FilamentBreezy\Pages\TwoFactorPage;

class BreezyCore implements Plugin
{
    use EvaluatesClosures;
    protected $engine;
    protected $cache;
    protected $myProfile;
    protected $avatarUploadComponent;
    protected $twoFactorAuthentication;
    protected $forceTwoFactorAuthentication;
    protected $twoFactorRouteAction;
    protected $registeredMyProfileComponents = [];
    protected $passwordUpdateRules = ['min:8'];
    protected bool $passwordUpdateRequireCurrent = true;
    protected $sanctumTokens = false;
    protected $sanctumPermissions = ["create", "view", "update", "delete"];

    public function __construct(Google2FA $engine, Repository $cache = null)
    {
        $this->engine = $engine;
        $this->cache = $cache;
    }

    public function getId(): string
    {
        return 'filament-breezy';
    }

    public static function make(): static
    {
        return app(static::class);
    }
    public function register(Panel $panel): void
    {
        $panel
            ->pages($this->preparePages());
        // If TwoFactor is enabled, register the middleware.
        if ($this->twoFactorAuthentication) {
            $panel->authMiddleware([MustTwoFactor::class]);
            Livewire::component('two-factor-page', Pages\TwoFactorPage::class);
        }
    }
    protected function preparePages(): array
    {
        $collection = collect();
        if ($this->myProfile) {
            $collection->push(Pages\MyProfilePage::class);
        }
        return $collection->toArray();
    }

    public function boot(Panel $panel): void
    {
        if ($this->myProfile) {
            if ($this->sanctumTokens) {
                Livewire::component('sanctum_tokens', SanctumTokens::class);
                $this->myProfileComponents([
                    'sanctum_tokens' => SanctumTokens::class
                ]);
            }
            if ($this->twoFactorAuthentication) {
                Livewire::component('two_factor_authentication', TwoFactorAuthentication::class);
                $this->myProfileComponents([
                    'two_factor_authentication' => TwoFactorAuthentication::class
                ]);
            }

            Livewire::component('personal_info', PersonalInfo::class);
            Livewire::component('update_password', UpdatePassword::class);
            $this->myProfileComponents([
                'personal_info' => PersonalInfo::class,
                'update_password' => UpdatePassword::class
            ]);

            if ($this->myProfile['shouldRegisterUserMenu']) {
                if ($panel->hasTenancy()) {
                    $tenantId = request()->route()->parameter('tenant');
                    if ($tenantId && $tenant = app($panel->getTenantModel())::where($panel->getTenantSlugAttribute() ?? 'id', $tenantId)->first()){
                        $panel->userMenuItems([
                            'account' => MenuItem::make()->url(Pages\MyProfilePage::getUrl(panel:$panel->getId(),tenant: $tenant)),
                        ]);
                    }
                } else {
                    $panel->userMenuItems([
                        'account' => MenuItem::make()->url(Pages\MyProfilePage::getUrl()),
                    ]);
                }
            }
        }
    }

    public function auth()
    {
        return Filament::getCurrentPanel()->auth();
    }

    public function getCurrentPanel()
    {
        return Filament::getCurrentPanel();
    }

    public function myProfile(bool $condition = true, bool $shouldRegisterUserMenu = true, bool $shouldRegisterNavigation = false, bool $hasAvatars = false, string $slug = 'my-profile'){
        $this->myProfile = get_defined_vars();
        return $this;
    }

    public function hasAvatars()
    {
        return $this->myProfile['hasAvatars'];
    }

    public function slug()
    {
        return $this->myProfile['slug'];
    }

    public function avatarUploadComponent(Closure $component)
    {
        $this->avatarUploadComponent = $component;
        return $this;
    }

    public function getAvatarUploadComponent()
    {
        $fileUpload = Forms\Components\FileUpload::make('avatar_url')
            ->label(__('filament-breezy::default.fields.avatar'))->avatar();
        return is_null($this->avatarUploadComponent) ? $fileUpload : $this->evaluate($this->avatarUploadComponent, namedInjections:[
            'fileUpload' => $fileUpload
        ]);
    }

    public function myProfileComponents(array $components)
    {
        $this->registeredMyProfileComponents = [
            ...$components,
            ...$this->registeredMyProfileComponents,
        ];

        return $this;
    }

    public function getRegisteredMyProfileComponents(): array
    {
        $components = collect($this->registeredMyProfileComponents)->filter(
            fn (string $component) => $component::canView()
        )->sortBy(
            fn (string $component) => $component::getSort()
        );

        if ($this->shouldForceTwoFactor()){
            $components = $components->only(['two_factor_authentication']);
        }
        return $components->all();
    }

    public function passwordUpdateRules(array | Password $rules, bool $requiresCurrentPassword = true)
    {
        $this->passwordUpdateRequireCurrent = $requiresCurrentPassword;
        $this->passwordUpdateRules = $rules;
        return $this;
    }

    public function getPasswordUpdateRequiresCurrent()
    {
        return $this->passwordUpdateRequireCurrent;
    }

    public function getPasswordUpdateRules()
    {
        return $this->passwordUpdateRules;
    }

    public function shouldRegisterNavigation(string $key)
    {
        return $this->{$key}['shouldRegisterNavigation'];
    }

    public function enableTwoFactorAuthentication(bool $condition = true, bool $force = false, string | Closure | array | null $action = TwoFactorPage::class)
    {
        $this->twoFactorAuthentication = $condition;
        $this->forceTwoFactorAuthentication = $force;
        $this->twoFactorRouteAction = $action;
        return $this;
    }

    public function getForceTwoFactorAuthentication(): bool
    {
        return $this->forceTwoFactorAuthentication;
    }

    public function getTwoFactorRouteAction(): string | Closure | array | null
    {
        return $this->twoFactorRouteAction;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function generateSecretKey()
    {
        return $this->engine->generateSecretKey();
    }

    public function getTwoFactorQrCodeSvg(string $url)
    {
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(150, 1, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd()
            )
        ))->writeString($url);

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }

    public function getQrCodeUrl($companyName, $companyEmail, $secret)
    {
        return $this->engine->getQRCodeUrl($companyName, $companyEmail, $secret);
    }

    public function verify(string $code, ?Authenticatable $user = null)
    {
        if (is_null($user)) {
            $user = Filament::auth()->user();
        }
        $secret = decrypt($user->two_factor_secret);

        $timestamp = $this->engine->verifyKeyNewer(
            $secret,
            $code,
            optional($this->cache)->get($key = 'filament.2fa_codes.' . md5($code))
        );

        if ($timestamp !== false) {
            optional($this->cache)->put($key, $timestamp, ($this->engine->getWindow() ?: 1) * 60);

            return true;
        }

        return false;
    }

    public function shouldForceTwoFactor(): bool
    {
        return $this->forceTwoFactorAuthentication && !$this->auth()->user()?->hasConfirmedTwoFactor();
    }

    public function enableSanctumTokens(bool $condition = true,?array $permissions = null)
    {
        $this->sanctumTokens = $condition;
        if (!is_null($permissions)){
            $this->sanctumPermissions = $permissions;
        }
        return $this;
    }

    public function getSanctumPermissions(): array
    {
        return collect($this->sanctumPermissions)->mapWithKeys(function($item,$key){
            $key = is_string($key) ? $key : strtolower($item);
            return [$key => $item];
        })->toArray();
    }

}
