<?php

namespace App\Filament\Advisor\Widgets;

use App\Models\Transaction;
use App\Models\ProfileTransaction;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TransactionStatus extends BaseWidget
{
    protected function getStats(): array
    {
        $profileId = Auth::user()->profiles->id;
        return [
            Stat::make('Opciones de Grado', ProfileTransaction::where('profile_id', $profileId)
                        ->whereHas('role', fn($q) => $q->where('id', 2))
                        ->whereHas('transaction', fn($q) => $q->where('status', 1))
                        ->count())
                ->description('En Progreso')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::Before)
                ->color('info')
                ->url(route('filament.advisor.resources.transactions.index', [
                    'activeTab' => 'En+Progreso',
            ])),
             Stat::make('Opciones de Grado', ProfileTransaction::where('profile_id', $profileId)
                        ->whereHas('role', fn($q) => $q->where('id', 2))
                        ->whereHas('transaction', fn($q) => $q->where('status', 2))
                        ->count())
                ->description('Completadas')
                ->descriptionIcon('heroicon-o-clipboard-document-check', IconPosition::Before)
                ->color('info')
                ->url(route('filament.advisor.resources.transactions.index', [
                    'activeTab' => 'Completadas',
            ])),
            Stat::make('Opciones de Grado', ProfileTransaction::where('profile_id', $profileId)
                        ->whereHas('role', fn($q) => $q->where('id', 2))
                        ->whereHas('transaction', fn($q) => $q->where('status', 3))
                        ->count())
                ->description('Por Certificar')
                ->descriptionIcon('heroicon-o-ellipsis-horizontal-circle', IconPosition::Before)
                ->color('info')
                ->url(route('filament.advisor.resources.transactions.index', [
                    'activeTab' => 'Por+Certificar',
            ])),
            Stat::make('Opciones de Grado', ProfileTransaction::where('profile_id', $profileId)
                        ->whereHas('role', fn($q) => $q->where('id', 2))
                        ->whereHas('transaction', fn($q) => $q->where('status', ))
                        ->count())
                ->description('Certificadas')
                ->descriptionIcon('heroicon-o-academic-cap', IconPosition::Before)
                ->color('info')
                ->url(route('filament.advisor.resources.transactions.index', [
                    'activeTab' => 'Certificado',
            ])),
        ];
    }
}
