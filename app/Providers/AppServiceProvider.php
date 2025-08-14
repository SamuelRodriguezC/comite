<?php

namespace App\Providers;

use App\Models\Transaction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\URL;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Support\Facades\FilamentColor;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
            // Forzar HTTPS en producción
        // if (app()->environment('production')) {
        //     URL::forceScheme('https');
        // }
        if (session()->pull('just_logged_in', false) && app()->environment('local')) {
            Filament::serving(function () {
                Filament::registerRenderHook('scripts.end', fn () => <<<HTML
                    <script>
                        if (!sessionStorage.getItem('reloadAfterLogin')) {
                            sessionStorage.setItem('reloadAfterLogin', 'true');
                            window.location.reload();
                        } else {
                            sessionStorage.removeItem('reloadAfterLogin');
                        }
                    </script>
                HTML);
            });
        }

        FilamentColor::register([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'teal' => Color::Teal,
                'success' => Color::Green,
                'warning' => Color::Amber,
        ]);


        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {

            $user = Auth::user();

            if (! $user) {
                return;
            }

            // Mapeo de paneles: id => label
            $panelMap = [
                'student'     => 'Estudiante',
                'evaluator'   => 'Evaluador',
                'advisor'     => 'Asesor',
                'coordinator' => 'Coordinador',
            ];

            $visiblePanels = [];    // ['advisor' => 'Asesor', ...]
            $panelIds = [];         // ['advisor', 'evaluator', ...]

            foreach ($panelMap as $panelId => $roleName) {
                if ($user->hasRole($roleName)) {
                    $visiblePanels[$panelId] = $roleName;
                    $panelIds[] = $panelId;
                }
            }

            // Caso 1: solo un rol → no mostrar switch
            if (count($panelIds) <= 1) {
                $panelSwitch
                    ->visible(false);
                return;
            }

            // Caso 2: exactamente dos roles → filtrar solo advisor/evaluator si están
            if (count($panelIds) === 2) {
                $limitedIds = [];
                $limitedLabels = [];

                foreach (['advisor', 'evaluator'] as $panelId) {
                    if (in_array($panelId, $panelIds)) {
                        $limitedIds[] = $panelId;
                        $limitedLabels[$panelId] = $visiblePanels[$panelId];
                    }
                }

                if (count($limitedIds) > 1) {
                    $panelSwitch
                        ->simple()
                        ->panels($limitedIds)
                        ->labels($limitedLabels)
                        ->visible(true); // <--- Y AÑADE ESTA LÍNEA AQUÍ TAMBIÉN
                }

                return;
            }

            // Caso 3: más de dos roles → mostrar todos
            $panelSwitch
                ->simple()
                ->panels($panelIds)
                ->labels($visiblePanels)
                ->visible(true); // <--- Y AÑADE ESTA LÍNEA AQUÍ TAMBIÉN

            $panelSwitch->renderHook('panels::topbar.start');

        });
    }
}
