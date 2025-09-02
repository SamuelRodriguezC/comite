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

        // Rango de últimos 12 meses
        $start = Carbon::now()->subMonths(11)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        // Consulta optimizada: una sola query con groupBy
        $transactions = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        // Construimos los meses con ceros si no hay datos
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('Y-m'));
        }

        $counts = $months->map(fn($month) => $transactions[$month] ?? 0);

        return [
            'labels' => $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Opciones Creadas',
                    'data' => $counts->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
            ],
        ];
    }
}
