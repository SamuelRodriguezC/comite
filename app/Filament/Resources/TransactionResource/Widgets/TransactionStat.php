<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TransactionStat extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tickets', Transaction::count())
                ->color('success')
                ->icon('heroicon-o-ticket')
                ->chart($this->getChartData('total')),

            Stat::make('Pendientes por Certificar', Transaction::where('status', 2)->count())
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->chart($this->getChartData('pending')),

            Stat::make('Certificados', Transaction::where('status', 3)->count())
                ->color('info')
                ->icon('heroicon-o-document-check')
                ->chart($this->getChartData('certified')),

            Stat::make('Tickets Habilitados', Transaction::where('status', 1)->count())
                ->color('primary')
                ->icon('heroicon-o-check-circle')
                ->chart($this->getChartData('enabled')),
        ];
    }

    // Esta función genera datos para los mini charts por día (últimos 7 días)
    protected function getChartData(string $type): array
    {
        $days = collect(range(6, 0))->map(function ($i) {
            return Carbon::now()->subDays($i)->startOfDay();
        });

        return $days->map(function ($day) use ($type) {
            $query = Transaction::whereDate('created_at', $day);

            if ($type === 'pending') {
                $query->where('status', 2);
            } elseif ($type === 'certified') {
                $query->where('status', 3);
            } elseif ($type === 'enabled') {
                $query->where('enabled', 1);
            }

            return $query->count();
        })->toArray();
    }
}
