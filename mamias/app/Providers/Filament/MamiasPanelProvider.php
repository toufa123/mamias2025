<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Support\Enums\Width;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;
use lockscreen\FilamentLockscreen\Lockscreen;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;
use lockscreen\FilamentLockscreen\Http\Middleware\LockerTimer;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;

class MamiasPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('mamias')
            ->path('mamias')
            ->spa(hasPrefetching: true)
            ->spaUrlExceptions(fn (): array => [
                url('/'),
            ])
            ->font('Roboto', provider: GoogleFontProvider::class)
            ->maxContentWidth(Width::Full)
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
            ->globalSearch(false)
            //->topNavigation()
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->databaseNotifications()
            ->darkMode(false)
            //->sidebarFullyCollapsibleOnDesktop()
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(fn () => view('assets/logo'))
            ->brandLogoHeight('2.5em')
            ->viteTheme('resources/css/filament/mamias/theme.css')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
//            ->colors([
//
//            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->plugins([
                RenewPasswordPlugin::make()
                    ->passwordExpiresIn(days: 30)
                    ->forceRenewPassword(),
                FilamentShieldPlugin::make()
                    ->navigationGroup('User Management')
                    ->navigationSort(-1)
                    ->navigationIcon('heroicon-o-home')         // string|Closure|null
                    ->activeNavigationIcon('heroicon-s-home') ,
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(app()->environment('local'))
                    ->users([
                        'Admin' => 'atef.ouerghi@spa-rac.org',
                        'User' => 'atef.ouerghi@gmail.com',
                    ]),
                EnvironmentIndicatorPlugin::make()
                    ->color(fn () => match (app()->environment()) {
                        'production' => null,
                        'staging' => color::Hex('#FF6B35'),
                        default => color::Hex('#004C97'),
                    })
                    ->showDebugModeWarningInProduction(),
                AuthUIEnhancerPlugin::make()
                    ->showEmptyPanelOnMobile(false)
                    ->formPanelPosition('right')
                    ->formPanelWidth('45%')
                    //->mobileFormPanelPosition('top')
                    ->emptyPanelBackgroundImageOpacity('50%')
                    ->emptyPanelBackgroundImageUrl('https://images.pexels.com/photos/3184418/pexels-photo-3184418.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
                FilamentProgressbarPlugin::make()->color('#29b'),
                Lockscreen::make()
                    ->usingCustomTableColumns('email', 'password') // Use custom table columns. Default:  email, password.
                    ->enableRateLimit(5) // Enable rate limit for the lockscreen. Default: Enable, 5 attempts in 1 minute.
                    //->setUrl() // Customize the lockscreen url.
                    ->enableIdleTimeout(500) // Enable auto lock during idle time. Default: Enable, 30 minutes.
                    //->disableDisplayName() // Display the name of the user based on the attribute supplied. Default: name
                    ->icon('heroicon-s-shield-check') // Customize the icon of the lockscreen.
                    ->enablePlugin() // Enable the plugin.
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
