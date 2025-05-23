<?php

namespace App\Filament\Student\Widgets;

use App\Models\Process;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TransactionWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
                Stat::make('Opción',
                Transaction::whereHas('profileTransactions', function ($query) {
                        $query->where('profile_id', Auth::user()->profiles->id)->where('role_id', 1);
                    })->count()
                )
                ->description('Tus Opciones')
                ->icon('heroicon-o-academic-cap')
                ->color('info')
                 ->url(route('filament.student.resources.transactions.index', [])),

                Stat::make(
                    'Procesos',
                    Process::where('state', 3)
                        ->whereHas('transaction', function ($query) {
                                $query->whereHas('profileTransactions', function ($query) {
                                    $query->where('profile_id', Auth::user()->profiles->id)
                                            ->where('role_id', 1);
                                });
                        })->count()
                )
                ->description('Procesos Pendientes')
                ->icon('heroicon-o-clock')
                ->color('info')
                ->url(route('filament.student.resources.processes.index', [
                    'tableFilters[state][value]' => 3,
                ])),

                Stat::make(
                    'Procesos',
                    Process::where('state', 6)
                        ->whereHas('transaction', function ($query) {
                                $query->whereHas('profileTransactions', function ($query) {
                                    $query->where('profile_id', Auth::user()->profiles->id)
                                            ->where('role_id', 1);
                                });
                        })->count()
                )
                ->description('Entregados')
                ->icon('heroicon-o-document-arrow-up')
                ->color('info')
                ->url(route('filament.student.resources.processes.index', [
                    'tableFilters[state][value]' => 6,
                ])),

                Stat::make(
                    'Procesos',
                    Process::where('state', 1)
                        ->whereHas('transaction', function ($query) {
                                $query->whereHas('profileTransactions', function ($query) {
                                    $query->where('profile_id', Auth::user()->profiles->id)
                                            ->where('role_id', 1);
                                });
                        })->count()
                )
                ->description('Aprobados')
                ->icon('heroicon-o-check-badge')
                ->color('info')
                ->url(route('filament.student.resources.processes.index', [
                    'tableFilters[state][value]' => 1,
                ])),
        ];
    }
}
