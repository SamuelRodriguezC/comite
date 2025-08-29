<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Shanerbaner82\PanelRoles\PanelRoles;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Monzer\FilamentChatifyIntegration\ChatifyPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class EvaluatorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('evaluator')
            ->path('evaluator')
            ->brandName('Comite - Panel Evaluador')
            ->unsavedChangesAlerts()
            ->darkMode(false)
            ->colors([
                'primary' => Color::Zinc,
            ])
            ->profile()
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Evaluator/Resources'), for: 'App\\Filament\\Evaluator\\Resources')
            ->discoverPages(in: app_path('Filament/Evaluator/Pages'), for: 'App\\Filament\\Evaluator\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Evaluator/Widgets'), for: 'App\\Filament\\Evaluator\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
                ChatifyPlugin::make(),
                PanelRoles::make()
                ->roleToAssign('Asesor')
                ->restrictedRoles(['Asesor', 'Evaluador', 'Coordinador', 'Super administrador']),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
