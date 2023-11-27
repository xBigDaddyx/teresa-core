<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Tenancy\EditCompanyProfile;
use App\Filament\Pages\Tenancy\RegisterCompany;
use App\Http\Middleware\AuthenticateLdap;
use App\Livewire\MyCustomProfile;
use App\Livewire\UpdatePassword;
use Domain\Admin\Models\Login;
use Domain\Users\Models\Company;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Auth\Middleware\Authenticate as MiddlewareAuthenticate;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class PurchasePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('purchase')
            ->path('purchase')
            ->authGuard('ldap')
            ->login(Login::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->sidebarCollapsibleOnDesktop()
            // ->topNavigation()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugins([
                \Xbigdaddyx\HarmonyFlow\HarmonyFlowPlugin::make(),
                BreezyCore::make()
                    ->myProfile(

                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                    )
                    ->enableTwoFactorAuthentication(
                        force: false, // force the user to enable 2FA before they can use the application (default = false)
                        //action: CustomTwoFactorPage::class // optionally, use a custom 2FA page
                    )
                    ->avatarUploadComponent(fn () => FileUpload::make('profile_photo_path')->avatar()->label(__('Avatar'))),
                FilamentPeekPlugin::make(),

                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Hasnayeen\Themes\ThemesPlugin::make(),
                \BezhanSalleh\FilamentLanguageSwitch\FilamentLanguageSwitchPlugin::make(),
            ])
            ->discoverResources(in: app_path('Filament/Purchase/Resources'), for: 'App\\Filament\\Purchase\\Resources')
            ->discoverPages(in: app_path('Filament/Purchase/Pages'), for: 'App\\Filament\\Purchase\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Purchase/Widgets'), for: 'App\\Filament\\Purchase\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                \LdapRecord\Laravel\Middleware\WindowsAuthenticate::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([

                //\App\Http\Middleware\AuthenticateLdap::class,
                Authenticate::class,
                //MiddlewareAuthenticate::class,
            ])
            ->tenantMiddleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->tenantMenuItems([
                'register' => MenuItem::make()->label('Register new company')
                    ->visible(fn (): bool => auth()->user()->hasRole('super-admin')),
                'profile' => MenuItem::make()->label('Company profile'),
            ])
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            ->favicon(asset('storage/images/favicon.ico'))
            //->tenantRoutePrefix('company')
            ->tenant(Company::class, 'short_name', 'company')
            ->tenantRegistration(RegisterCompany::class)
            ->tenantProfile(EditCompanyProfile::class);
    }
}
