<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Calendar;
use App\Filament\Pages\LegalNotices;
use App\Filament\Pages\PrivacyPolicies;
use App\Filament\Pages\UserAccount;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class MainPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('main')
            ->path('')
            ->colors([
                'primary' => Color::Pink,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
                Calendar::class,
                LegalNotices::class,
                PrivacyPolicies::class,
                UserAccount::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Mon compte')
                    ->url(fn (): string => UserAccount::getUrl())
                    ->icon('heroicon-o-identification'),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
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
            ])
            ->topNavigation()
            ->favicon(asset('images/reut.png'))
            ->brandLogo(asset('images/reut.png'))
            ->brandLogoHeight('3.5rem')
            ->brandName('RéUT - Réservation des salles de réunion')
            ->darkMode(false)
            ->plugin(FilamentFullCalendarPlugin::make()
                ->selectable()
                ->editable()
                ->timezone(config('app.timezone'))
                ->locale('fr')
                ->plugins(['dayGrid', 'timeGrid'])
                ->config([])
            );
    }
}
