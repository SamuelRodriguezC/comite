<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use App\Models\ProfileTransaction;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TransactionStat extends BaseWidget
{
   protected function getStats(): array
{
    $profile = Auth::user()?->profiles;

    if (!$profile) {
        return [];
    }

    $baseQuery = $this->evaluadorTransactions($profile->id);

    return [
        Stat::make('Total Opciones Asignadas', (clone $baseQuery)->count())
            ->color('info')
            ->icon('heroicon-o-academic-cap')
            ->url(route('filament.evaluator.resources.transactions.index', [

            ]))
            ->chart($this->getChartData('total')),

        Stat::make('Opciones', (clone $baseQuery)
                ->whereHas('transaction', fn($q) => $q->where('enabled', 1))
                ->count())
            ->color('info')
            ->description('Habilitadas')
            ->url(route('filament.evaluator.resources.transactions.index', [
                    'tableFilters[enabled][value]' => 1,
            ]))
            ->icon('heroicon-o-check-circle'),

        Stat::make('Opciones', (clone $baseQuery)
                ->whereHas('transaction', fn($q) => $q->where('component', 1))
                ->count())
            ->color('success')
            ->description('Investigativas')
            ->url(route('filament.evaluator.resources.transactions.index', [
                    'tableFilters[component][value]' => 1,
            ]))
            ->icon('heroicon-o-document-magnifying-glass'),

        Stat::make('Opciones', (clone $baseQuery)
                ->whereHas('transaction', fn($q) => $q->where('component', 2))
                ->count())
            ->color('warning')
            ->description('No Investigativas')
            ->url(route('filament.evaluator.resources.transactions.index', [
                    'tableFilters[component][value]' => 2,
            ]))
            ->icon('heroicon-o-document-text'),
    ];
}


    // Filtra las transacciones asociadas al perfil con rol Evaluador (id = 3)
    private function evaluadorTransactions(int $profileId)
    {
        return ProfileTransaction::query()
            ->where('profile_id', $profileId)
            ->whereHas('role', fn($q) => $q->where('id', 3)); // Rol Evaluador
    }

    // Retorna datos para un gráfico de los últimos 7 días según el tipo especificado
    protected function getChartData(string $type): array
    {
        // Generar colección de fechas desde hace 6 días hasta hoy
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->startOfDay());

        return $days->map(function ($day) use ($type) {
            $query = Transaction::whereDate('created_at', $day);

            // Filtrar según el tipo solicitado
            return match ($type) {
                'pending' => $query->where('status', 2)->count(), // Transacciones pendientes
                'certified' => $query->where('status', 3)->count(),  // Transacciones certificadas
                'enabled' => $query->where('enabled', 1)->count(), // Transacciones habilitadas
                default => $query->count(), // Total por día
            };
        })->toArray(); // Devolver los datos como array
    }
}
