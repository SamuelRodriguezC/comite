<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OptionChartNoInvestigative extends ChartWidget
{
    // Título que se muestra en la parte superior del widget
    protected static ?string $heading = 'Opciones de grado No Investigativas más solicitadas';

    // Orden en el que se muestra el widget en el panel (menor = más arriba)
    protected static ?int $sort = 3;

    /**
     * Obtiene los datos que se mostrarán en la gráfica.
     * Retorna un array con etiquetas y datos contados por opción.
     */
    protected function getData(): array
    {
        // Consulta los datos de las 5 opciones más solicitadas con componente = 1
        $data = DB::table('transactions')
            ->select('options.option', DB::raw('count(*) as total'))
            ->join('options', 'transactions.option_id', '=', 'options.id')
            ->where('options.component', 2) // Solo componente No investigativo
            ->groupBy('options.option')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $colors = [
            '#af1a09',
            '#ccc9ca',
            '#c39b04',
            '#374151',
            '#141414',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Solicitudes',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $data->pluck('option'),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'display' => false, // Oculta el eje X
                ],
                'y' => [
                    'display' => false, // Oculta el eje Y
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
