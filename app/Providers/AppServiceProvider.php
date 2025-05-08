<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
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
                        ->panels($limitedIds)         // ✅ solo los IDs
                        ->labels($limitedLabels)       // ✅ etiquetas visibles
                        ->visible(true);
                }

                return;
            }

            // Caso 3: más de dos roles → mostrar todos
            $panelSwitch
                ->simple()
                ->panels($panelIds)
                ->labels($visiblePanels)
                ->visible(true);
        });
    }
}
