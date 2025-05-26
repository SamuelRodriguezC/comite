<?php

namespace App\Filament\Advisor\Widgets;

use App\Enums\Status;
use App\Enums\Enabled;
use App\Enums\Component;
use App\Models\Transaction;
use Filament\Tables\Columns\IconColumn;
use App\Models\ProfileTransaction;
use Illuminate\Support\Facades\Auth;
use Tables\Columns\ProgressBarColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\TableWidget as BaseWidget;
use RyanChandler\FilamentProgressColumn\ProgressColumn;

class TransactionProgress extends BaseWidget
{
    protected static ?string $heading = 'Progreso de Transacciones que Asesoras';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';


    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        $profileId = $user->profiles->id ?? null;

        if (!$profileId) {
            return Transaction::query()->whereRaw('0 = 1'); // vacío
        }

        $transactionIds = ProfileTransaction::where('profile_id', $profileId)
            ->where('role_id', 2)
            ->pluck('transaction_id');

        return Transaction::query()
            ->whereIn('id', $transactionIds)
            ->with('option');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('# Opción ')
                ->searchable()
                ->sortable(),

            TextColumn::make('component')
                ->label('Componente')
                ->formatStateUsing(fn ($state) => Component::from($state)->getLabel())
                ->searchable(),

            TextColumn::make('courses')
                ->label('Carrreras')
                ->limit(25),

            IconColumn::make('enabled')
                    ->label('Habilitado')
                    ->icon(fn ($state) => Enabled::from($state)->getIcon())
                    ->color(fn ($state) => Enabled::from($state)->getColor()),

            TextColumn::make('option.option')
                ->label('Opción')
                ->limit(25)
                ->searchable(),

            TextColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => Status::tryFrom($state)?->getLabel())
                    ->badge()
                    ->color(fn ($state) => Status::tryFrom($state)?->getColor()),
            ProgressColumn::make('progress')
                ->label('Progreso')
                ->progress(function ($record) {
                    if (!$record || !isset($record->status)) {
                        return 0;
                    }

                    return match ((int) $record->status) {
                        Status::ENPROGRESO->value => 25,
                        Status::COMPLETADO->value => 50,
                        Status::PORCERTIFICAR->value => 75,
                        Status::CERTIFICADO->value => 100,
                        default => 0,
                    };
                })
                ->color('info')
            ];
        }
}
