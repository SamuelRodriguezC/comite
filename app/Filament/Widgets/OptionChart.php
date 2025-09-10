<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OptionChart extends ChartWidget
{
    protected static ?string $heading = 'Opciones de grado Investigativas y No Investigativas más solicitadas';
    // protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Top 5 por componente en SQL
        $investigative = DB::table('transactions')
            ->select('options.option', DB::raw('count(*) as total'))
            ->join('options', 'transactions.option_id', '=', 'options.id')
            ->where('options.component', 1)
            ->groupBy('options.option')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'option');

        $noInvestigative = DB::table('transactions')
            ->select('options.option', DB::raw('count(*) as total'))
            ->join('options', 'transactions.option_id', '=', 'options.id')
            ->where('options.component', 2)
            ->groupBy('options.option')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'option');

        // Etiquetas únicas combinadas
        $labels = $investigative->keys()->merge($noInvestigative->keys())->unique()->values();

        $dataInvestigative = $labels->map(fn($label) => $investigative[$label] ?? 0);
        $dataNoInvestigative = $labels->map(fn($label) => $noInvestigative[$label] ?? 0);

        $colors = [
            'investigative' => 'rgba(54, 162, 235, 0.6)',
            'borderInvestigative' => 'rgb(54, 162, 235)',
            'noInvestigative' => 'rgba(255, 159, 64, 0.6)',
            'borderNoInvestigative' => 'rgb(255, 159, 64)',
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Investigativas',
                    'data' => $dataInvestigative,
                    'backgroundColor' => $colors['investigative'],
                    'borderColor' =>  $colors['borderInvestigative'],
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'No Investigativas',
                    'data' => $dataNoInvestigative,
                    'backgroundColor' => $colors['noInvestigative'],
                    'borderColor' => $colors['borderNoInvestigative'],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
    protected function getOptions(): array
    {

        return [
            'scales' => [
                'x' => [
                    'stacked' => false,
                    'display' => true,
                    'ticks' => [],
                ],
                'y' => [
                    'stacked' => false,
                    'display' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }
}
