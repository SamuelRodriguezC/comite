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
        Stat::make('Total Opciones Asignados', (clone $baseQuery)->count())
            ->color('info')
            ->icon('heroicon-o-academic-cap')
            ->url(route('filament.evaluator.resources.transactions.index', [

            ]))
            ->chart($this->getChartData('total')),

        Stat::make('Opciones', (clone $baseQuery)
                ->whereHas('transaction', fn($q) => $q->where('enabled', 1))
                ->count())
            ->color('info')
            ->description('Habilitados')
            ->url(route('filament.evaluator.resources.transactions.index', [
                    'tableFilters[enabled][value]' => 1,
            ]))
            ->icon('heroicon-o-check-circle'),

        Stat::make('Opciones', (clone $baseQuery)
                ->whereHas('transaction', fn($q) => $q->where('status', 4))
                ->count())
            ->color('success')
            ->description('Certificados')
            ->url(route('filament.evaluator.resources.transactions.index', [
                    'tableFilters[status][value]' => 4,
            ]))
            ->icon('heroicon-o-academic-cap'),

        Stat::make('Opciones', (clone $baseQuery)
                ->whereHas('transaction', fn($q) => $q->where('status', 3))
                ->count())
            ->color('warning')
            ->description('Pendientes por Certificar')
            ->url(route('filament.evaluator.resources.transactions.index', [
                    'tableFilters[status][value]' => 3,
            ]))
            ->icon('heroicon-o-clock'),
    ];
}


    private function evaluadorTransactions(int $profileId)
    {
        return ProfileTransaction::query()
            ->where('profile_id', $profileId)
            ->whereHas('role', fn($q) => $q->where('name', 'evaluador'));
    }

    protected function getChartData(string $type): array
    {
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->startOfDay());

        return $days->map(function ($day) use ($type) {
            $query = Transaction::whereDate('created_at', $day);

            return match ($type) {
                'pending' => $query->where('status', 2)->count(),
                'certified' => $query->where('status', 3)->count(),
                'enabled' => $query->where('enabled', 1)->count(),
                default => $query->count(),
            };
        })->toArray();
    }
}
