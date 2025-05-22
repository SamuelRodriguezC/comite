<?php

namespace App\Filament\Evaluator\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EvaluationMonthEvaluator extends ChartWidget
{
    protected static ?string $heading = 'Evaluaciones por Mes';
    protected static string $color = 'info';
    protected static ?int $sort = 3;
    // Cantidad de meses hacia atrás
    protected int $monthsBack = 12;

    protected function getData(): array
    {
        $startDate = now()->subMonths($this->monthsBack)->startOfMonth();

        // Agrupamos por mes/año
        $evaluaciones = DB::table('comments')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $this->monthsBack; $i++) {
            $month = now()->subMonths($this->monthsBack - 1 - $i)->format('Y-m');
            $labels[] = Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');
            $data[] = $evaluaciones[$month]->count ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Evaluaciones',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
