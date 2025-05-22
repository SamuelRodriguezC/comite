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
            Stat::make('Generar Acta', Transaction::where('certification', 2)->count())
                ->description('Pendientes')
                ->icon('heroicon-o-academic-cap')
                ->color('info')
                ->url(route('filament.coordinator.resources.transactions.index', [
                    'tableFilters[certification][value]' => 2,
                ])),
            // Procesos Pendientes y con archivos en Etapa de Solicitud
            Stat::make('Solicitudes', Process::where('stage_id', 1)->where('state', 6)->whereNotNull('requirement')->where('requirement', '!=', '')->count())
                ->description('Entregadas Pendiente Revisión')
                ->icon('heroicon-o-user-plus')
                ->color('info')
                ->url(route('filament.coordinator.resources.process-aplications.index', [
                    'activeTab' => 'Entregado',
                ])),

            // Procesos Pendientes  y con archivos en Etapa de Entrega
            Stat::make('Entregas', Process::where('stage_id', 2)->where('state', 6)->whereNotNull('requirement')->where('requirement', '!=', '')->count())
                ->description('Entregadas Pendiente Revisión')
                ->icon('heroicon-o-document-arrow-up')
                ->color('info')
                ->url(route('filament.coordinator.resources.process-submits.index', [
                    'activeTab' => 'Entregado',
                ])),

            // Procesos Pendientes  y con archivos en Etapas de Primera y Segunda Corrección
            Stat::make('Correcciones', Process::whereIn('stage_id', [3, 4])->where('state', 6)->whereNotNull('requirement')->where('requirement', '!=', '')->count())
                ->description('Entregadas Pendiente Revisión')
                ->icon('heroicon-o-pencil-square')
                ->color('info')
                 ->url(route('filament.coordinator.resources.process-corrections.index', [
                    'activeTab' => 'Entregado',
                ])),

        ];
    }
}
