<?php

namespace App\Filament\Widgets;

use App\Models\Process;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProcessesWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            // Procesos Pendientes en Etapa de Solicitud
            Stat::make('Solicitudes', Process::where('stage_id', 1)->where('state', 3)->count())
                ->description('Pendientes')
                ->icon('heroicon-o-user-plus')
                ->color('warning'),
                // ->chart([40, 70, 40, 70, 40, 70]),
            // Procesos Pendientes en Etapa de Entrega
            Stat::make('Entregas', Process::where('stage_id', 2)->where('state', 3)->count())
                ->description('Pendientes Por Revisión')
                ->icon('heroicon-o-document-arrow-up')
                ->color('warning'),
            // Procesos Pendientes en Etapas de Primera y Segunda Corrección
            Stat::make('Correcciones', Process::whereIn('stage_id', [3, 4])->where('state', 3)->count())
                ->description('Pendientes Por Revisión')
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
        ];
    }
}
