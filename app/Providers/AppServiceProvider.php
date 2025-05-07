<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $user = Auth::user();
        
            // Verifica si hay un usuario autenticado
            if (! $user) {
                return;
            }
        
            // Mapea roles a sus respectivos paneles
            $roleToPanel = [
                1 => 'student',
                3 => 'evaluator',
                2 => 'advisor',
                4 => 'coordinator',
            ];
        
            // Construye el array de paneles visibles basados en los roles del usuario
            $availablePanels = [];
            foreach ($roleToPanel as $role => $panel) {
                if ($user->hasRole($role)) {
                    $availablePanels[$panel] = $role; // panel => etiqueta
                }
            }
        
            // Configura el panel switch solo con los paneles accesibles
            $panelSwitch
                ->simple()
                ->labels($availablePanels)
                ->visible(count($availablePanels) > 1); // Solo mostrar si hay más de un panel visible
        });
    }
}
