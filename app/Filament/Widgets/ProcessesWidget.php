<?php

namespace App\Filament\Widgets;

use App\Models\Process;
use App\Models\Transaction;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProcessesWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Certificaciónes', Transaction::where('certification', 2)->count())
                ->description('Pendientes')
                ->icon('heroicon-o-academic-cap')
                ->color('warning')
                ->chart([80, 10, 50, 50, 60, 10]),
            // Procesos Pendientes en Etapa de Solicitud
            Stat::make('Solicitudes', Process::where('stage_id', 1)->where('state', 3)->count())
                ->description('Pendientes')
                ->icon('heroicon-o-user-plus')
                ->color('warning')
                ->chart([10, 10, 50, 50, 60, 10]),
            // Procesos Pendientes en Etapa de Entrega
            Stat::make('Entregas', Process::where('stage_id', 2)->where('state', 3)->count())
                ->description('Pendientes Por Revisión')
                ->icon('heroicon-o-document-arrow-up')
                ->color('warning')
                ->chart([10, 10, 50, 50, 60, 100]),
            // Procesos Pendientes en Etapas de Primera y Segunda Corrección
            Stat::make('Correcciones', Process::whereIn('stage_id', [3, 4])->where('state', 3)->count())
                ->description('Pendientes Por Revisión')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                 ->chart([100, 10, 50, 50, 60, 100]),

                 
        ];
    }
}
