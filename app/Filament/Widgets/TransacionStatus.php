<?php

namespace App\Filament\Widgets;

use App\Models\Process;
use App\Models\Transaction;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransacionStatus extends BaseWidget
{
    protected static ?int $sort = 1;

        protected function getHeading(): string
    {
        return 'Resumen de Opciones de Grado';
    }

    protected function getDescription(): ?string
    {
        return 'Contador del estado de las Opciones de Grado';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Opciones de Grado', Transaction::where('status', 1)->count())
                ->description('En Progreso')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::Before)
                ->color('info')
                ->url(route('filament.coordinator.resources.transactions.index', [
                    'activeTab' => 'En+Progreso',
            ])),
         Stat::make('Opciones de Grado', Transaction::where('status', 2)->count())
                ->description('Completadas')
                ->descriptionIcon('heroicon-o-clipboard-document-check', IconPosition::Before)
                ->color('info')
                ->url(route('filament.coordinator.resources.transactions.index', [
                    'activeTab' => 'Completadas',
            ])),
            Stat::make('Opciones de Grado', Transaction::where('status', 3)->count())
                ->description('Por Certificar')
                ->descriptionIcon('heroicon-o-ellipsis-horizontal-circle', IconPosition::Before)
                ->color('info')
                ->url(route('filament.coordinator.resources.transactions.index', [
                    'activeTab' => 'Por+Certificar',
            ])),
            Stat::make('Opciones de Grado', Transaction::where('status', 4)->count())
                ->description('Certificadas')
                ->descriptionIcon('heroicon-o-academic-cap', IconPosition::Before)
                ->color('info')
                ->url(route('filament.coordinator.resources.transactions.index', [
                    'activeTab' => 'Certificado',
            ])),
        ];

    }
    protected function getColumns(): int
    {
        return 4;
    }
}
