<?php

namespace App\Filament\Evaluator\Widgets;

use App\Enums\State;
use App\Models\Process;
use App\Models\ProfileTransaction;
use Filament\Widgets\PieChartWidget;
use Illuminate\Support\Facades\Auth;

class EvaluatonStateEvaluator extends PieChartWidget
{
    protected static ?string $heading = 'Estado actual de tus evaluaciones';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $user = Auth::user();
        $profile = $user->profiles ?? null;

        if (!$profile) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Obtener transacciones donde el perfil tiene rol evaluador
        $transactionIds = ProfileTransaction::query()
            ->where('profile_id', $profile->id)
            ->whereHas('role', fn($q) => $q->where('name', 'evaluador'))
            ->pluck('transaction_id');

        // Contar procesos agrupados por estado
        $statusCounts = Process::query()
            ->whereIn('transaction_id', $transactionIds)
            ->selectRaw('state, COUNT(*) as count')
            ->groupBy('state')
            ->pluck('count', 'state')
            ->toArray();

        // Usar el enum para etiquetas y colores
        $labels = [];
        $data = [];

        foreach (State::cases() as $state) {
            $labels[] = $state->getLabel();
            $data[] = $statusCounts[$state->value] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Procesos',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(201, 203, 207, 0.6)'
                    ],
                    'borderColor' => [
                        'rgb(75, 192, 192)',
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(54, 162, 235)',
                        'rgb(201, 203, 207)'
                    ],
                    'borderWidth' => 1
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
        return 'bar';
    }
}
