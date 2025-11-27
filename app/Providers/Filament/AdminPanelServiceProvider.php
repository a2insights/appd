<?php

namespace App\Providers\Filament;

use A2Insights\FilamentSaas\User\Filament\Components\Phone;
use A2Insights\FilamentSaas\User\Filament\Components\Username;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\AppdInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Platform;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Wallo\FilamentCompanies\Pages\Auth\Login;

class AdminPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->homeUrl('/admin')
            ->path('admin')
            ->default()
            ->login(Login::class)
            ->registration(false)
            // ->passwordReset()
            ->emailVerification()
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            // ->sidebarFullyCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            // ->unsavedChangesAlerts()
            // ->pages([])
            ->viteTheme('resources/css/filament/admin/admin-theme.css')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->plugins([
                \Kenepa\Banner\BannerPlugin::make()->persistsBannersInDatabase(),
                \Awcodes\FilamentQuickCreate\QuickCreatePlugin::make()
                    ->includes([
                        \A2Insights\FilamentSaas\User\Filament\UserResource::class,
                    ]),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()->myProfile(
                    shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                    shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                    hasAvatars: true, // Enables the avatar upload form component (default = false)
                    slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                )->enableTwoFactorAuthentication(
                    force: false, // force the user to enable 2FA before they can use the application (default = false)
                    // action: CustomTwoFactorPage::class // optionally, use a custom 2FA page
                )
                    // TODO: Make configurable
                    // ->enableSanctumTokens(
                    //     permissions: ['create', 'update', 'view', 'delete'] // optional, customize the permissions (default = ["create", "view", "update", "delete"])
                    // )
                    ->myProfileComponents([Phone::class, Username::class])
                    ->avatarUploadComponent(fn ($fileUpload) => $fileUpload
                        ->visibility('private')
                        ->directory('avatars')
                        ->disk('avatars')),
                \Hasnayeen\Themes\ThemesPlugin::make()->canViewThemesPage(fn () => (bool) auth()?->user()?->hasRole('super_admin')),
                \A2Insights\FilamentSaas\User\UserPlugin::make(),
                \A2Insights\FilamentSaas\Features\FeaturesPlugin::make(),
                \A2Insights\FilamentSaas\Settings\SettingsPlugin::make(),
            ])
            ->widgets([
                AppdInfoWidget::class,
                Widgets\AccountWidget::class,
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
                \A2Insights\FilamentSaas\Settings\Http\Middleware\Locale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \Cog\Laravel\Ban\Http\Middleware\ForbidBannedUser::class,
            ])
            ->tenantMiddleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            })
           ->maxContentWidth('full');   
    }
}   
