<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Tenancy\EditCompanyProfile;
use App\Filament\Pages\Tenancy\RegisterCompany;
use Domain\Admin\Models\Login;
use Domain\Users\Models\Company;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
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
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('ldap')
            ->login(Login::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->sidebarCollapsibleOnDesktop()
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        hasAvatars: false, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                    ),

                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Hasnayeen\Themes\ThemesPlugin::make(),
                \BezhanSalleh\FilamentLanguageSwitch\FilamentLanguageSwitchPlugin::make(),
            ])
            //->topNavigation()

            // ->navigationItems([
            //     NavigationItem::make('Personal Access Tokens')
            //         ->label(static fn (): string => __('filament-companies::default.navigation.links.tokens'))
            //         ->icon('heroicon-o-key')
            //         ->url(static fn () => url(PersonalAccessTokens::getUrl())),
            // ])
            ->colors([
                'primary' => Color::Amber,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([

                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                //\Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])

            ->authMiddleware([
                Authenticate::class,
                //MiddlewareAuthenticate::class,
                //\App\Http\Middleware\AuthenticateLdap::class,
            ])
            ->tenantMiddleware([

                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->tenantMenuItems([
                'register' => MenuItem::make()->label('Register new company')
                    ->visible(fn (): bool => auth()->user()->hasRole('super-admin')),
                'profile' => MenuItem::make()->label('Company profile'),
            ])


            ->favicon(asset('storage/images/favicon.ico'))
            //->tenantRoutePrefix('company')
            ->tenant(Company::class, 'short_name', 'company')
            ->tenantRegistration(RegisterCompany::class)
            ->tenantProfile(EditCompanyProfile::class);
    }
}
