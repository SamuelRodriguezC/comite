<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\Widget;
use Filament\Widgets\LineChartWidget;

class CreatedTransactions extends LineChartWidget
{
    protected static ?string $heading = 'Tickets creados por mes';
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('Y-m'));
        }

        $counts = $months->map(function ($month) {
            [$year, $m] = explode('-', $month);
            return Transaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->count();
        });

        return [
            'labels' => $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->format('M Y'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Tickets Creados',
                    'data' => $counts->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
            ],
        ];
    }
}
