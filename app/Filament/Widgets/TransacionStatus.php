<?php

namespace App\Filament\Widgets;

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
        // Obtener conteos en una sola consulta
        $counts = Transaction::selectRaw('status, COUNT(*) as total')
            ->whereIn('status', [1, 2, 3, 4])
            ->groupBy('status')
            ->pluck('total', 'status');

        // Configuración de cada estado
        $statuses = [
            1 => [
                'label' => 'En Progreso',
                'icon'  => 'heroicon-o-arrow-trending-up',
                'color' => 'info',
                'tab'   => 'En+Progreso',
            ],
            2 => [
                'label' => 'Completadas',
                'icon'  => 'heroicon-o-clipboard-document-check',
                'color' => 'success',
                'tab'   => 'Completado',
            ],
            3 => [
                'label' => 'Por Certificar',
                'icon'  => 'heroicon-o-ellipsis-horizontal-circle',
                'color' => 'warning',
                'tab'   => 'Por+Certificar',
            ],
            4 => [
                'label' => 'Certificadas',
                'icon'  => 'heroicon-o-academic-cap',
                'color' => 'success',
                'tab'   => 'Certificado',
            ],
        ];

        // Generar los Stats dinámicamente
        $stats = [];
        foreach ($statuses as $status => $data) {
            $stats[] = Stat::make('Opciones de Grado', $counts[$status] ?? 0)
                ->description($data['label'])
                ->descriptionIcon($data['icon'], IconPosition::Before)
                ->color($data['color'])
                ->url(route('filament.coordinator.resources.transactions.index') . '?activeTab=' . $data['tab']);
        }

        return $stats;
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
