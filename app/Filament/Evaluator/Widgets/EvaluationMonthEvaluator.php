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

    /**
     * Retorna los datos que se mostrarán en el gráfico.
     * Agrupa los comentarios por mes de creación y los cuenta.
     */
    protected function getData(): array
    {
        // Fecha de inicio (hace 12 meses desde el primer día del mes actual)
        $startDate = now()->subMonths($this->monthsBack)->startOfMonth();

        // Consulta que agrupa los comentarios por mes y cuenta cuántos hay por cada uno
        $evaluaciones = DB::table('comments')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month'); // Convierte la colección para acceso rápido por clave 'month'

        $labels = []; // Etiquetas para el eje X (meses)
        $data = []; // Datos para el eje Y (conteo de comentarios)

        // Recorre los últimos 12 meses para generar etiquetas y valores
        for ($i = 0; $i < $this->monthsBack; $i++) {
            $month = now()->subMonths($this->monthsBack - 1 - $i)->format('Y-m');
            $labels[] = Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');
            $data[] = $evaluaciones[$month]->count ?? 0; // Si no hay datos, asigna 0
        }

        // Retorna los datos en el formato que espera Filament Charts
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

    /**
     * Define el tipo de gráfico (línea en este caso)
     */

    protected function getType(): string
    {
        return 'line';
    }
}
