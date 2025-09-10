<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\Widget;
use Filament\Widgets\LineChartWidget;

class CreatedTransactions extends LineChartWidget
{
    protected static ?string $heading = 'Opciones creadas por mes';
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        Carbon::setLocale('es');

        $now = Carbon::now();

        // Rango de últimos 12 meses
        $start = $now->copy()->subMonths(11)->startOfMonth();
        $end = $now->copy()->endOfMonth();

        // Consulta optimizada: una sola query con groupBy
        $transactions = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        // Construimos los meses con ceros si no hay datos
        $months = collect(range(0, 11))->map(fn($i) => $now->copy()->subMonths(11 - $i));

        return [
            'labels' => $months->map(fn($m) => $m->translatedFormat('M Y'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Opciones Creadas',
                    'data' => $months->map(fn($m) => $transactions[$m->format('Y-m')] ?? 0 )->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],


            ],
        ];
    }
}
