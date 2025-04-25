<?php

namespace App\Filament\Widgets;

use App\Models\Process;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProcessAplicationsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Solicitudes', Process::where('stage_id', 1)->where('state', 3)->count())
                ->description('Pendientes')
                ->icon('heroicon-o-user-plus')
                ->color('success')
                ->chart([40, 70, 40, 70, 40, 70]),
        ];
    }
}
