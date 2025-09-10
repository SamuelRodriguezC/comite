<?php

namespace App\Filament\Widgets;

use App\Enums\State;
use App\Models\Process;
use Filament\Widgets\Widget;
use App\Models\ProfileTransaction;
use App\Models\Transaction;
use Filament\Widgets\PieChartWidget;

class ProcessState extends PieChartWidget
{
    // protected static string $view = 'filament.widgets.process-state';
    protected static ?string $heading = 'Estados de todos los proceos';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        // Contar procesos agrupados por estado (sin pluck innecesario)
        $statusCounts = Process::query()
            ->selectRaw('state, COUNT(*) as count')
            ->groupBy('state')
            ->pluck('count', 'state');

        // Usar enum para etiquetas y asegurar que todos los estados estén en el gráfico

        $labels = collect(State::cases())->map(fn($state) => $state->getLabel());
        $data = collect(State::cases())->map(fn($state) => $statusCounts[$state->value]  ?? 0);

        return [
            'datasets' => [
                [
                    'label' => 'Procesos',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 0, 0, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(201, 203, 207, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                    ],
                    'borderColor' => [
                        'rgb(75, 192, 192)',
                        'rgba(255, 0, 0, 0.78)',
                        'rgb(255, 159, 64)',
                        'rgb(201, 203, 207)',
                        'rgba(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }
    public function getColumns(): int | string | array
    {
        return [
            'md' => 1,
            'xl' => 1,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
