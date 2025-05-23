<?php

namespace App\Filament\Evaluator\Widgets;

use App\Models\Process;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TransactionsWidgetEvaluator extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Opciones',
                //----------------------- TRANSACCIONES HABILITADAS DEL EVALUADOR -----------------------
                Transaction::whereHas('profileTransactions', function ($query) {
                        $query->where('profile_id', Auth::user()->profiles->id)->where('role_id', 3);
                    })->where('enabled', 1)->count()
                )
                ->description('Opciones asignados y habilitados')
                ->icon('heroicon-o-academic-cap')
                ->color('info')
                 ->url(route('filament.evaluator.resources.transactions.index', [
                    'activeTab' => 'habilitado',
                ])),

                // ----------------------- SOLICITUDES HABILITADAS Y PENDIENTES DEL EVALUADOR -----------------------
                Stat::make(
                    'Solicitudes',
                    Process::where('stage_id', 1)
                        ->where('state', 6)
                        ->whereHas('transaction', function ($query) {
                            $query->where('enabled', 1)
                                ->whereHas('profileTransactions', function ($query) {
                                    $query->where('profile_id', Auth::user()->profiles->id)
                                            ->where('role_id', 3); // Rol Evaluador
                                });
                        })->count()
                )
                ->description('Habilitadas Entregadas')
                ->icon('heroicon-o-document-arrow-up')
                ->color('info')
                ->url(route('filament.evaluator.resources.process-aplications.index', [
                    'activeTab' => 'Entregado',
                ])),

                // ----------------------- ENTREGAS HABILITADAS Y PENDIENTES DEL EVALUADOR -----------------------
                Stat::make(
                    'Entregas',
                    Process::where('stage_id', 2)
                        ->where('state', 6)
                        //->whereRaw("TRIM(requirement) != ''") Buscar procesos que sean pendientes y tengan archivos subidos
                        ->whereHas('transaction', function ($query) {
                            $query->where('enabled', 1)
                                ->whereHas('profileTransactions', function ($query) {
                                    $query->where('profile_id', Auth::user()->profiles->id)
                                            ->where('role_id', 3); // Rol Evaluador
                                });
                        })->count()
                )
                ->description('Habilitadas y Entregadas')
                ->icon('heroicon-o-document-arrow-up')
                ->color('info')
                 ->url(route('filament.evaluator.resources.process-submits.index', [
                    'activeTab' => 'Entregado',
                ])),

                // ----------------------- CORRECCIONES (PRIMERA Y SEGUNDA) PENDIENTES DEL EVALUADOR -----------------------
                Stat::make(
                    'Correcciones',
                    Process::whereIn('stage_id', [3, 4])
                        ->where('state', 6)
                      //->whereRaw("TRIM(requirement) != ''") Buscar procesos que sean pendientes y tengan archivos subidos
                        ->whereHas('transaction', function ($query) {
                            $query->where('enabled', 1)
                                ->whereHas('profileTransactions', function ($query) {
                                    $query->where('profile_id', Auth::user()->profiles->id)
                                            ->where('role_id', 3); // Rol Evaluador
                                });
                        })->count()
                )
                ->description('Habilitadas y Entregadas')
                ->icon('heroicon-o-document-arrow-up')
                ->color('info')
                 ->url(route('filament.evaluator.resources.process-submits.index', [
                    'activeTab' => 'Entregado',
                ])),

        ];
    }
}
