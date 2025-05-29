<?php

namespace App\Filament\Student\Widgets;

use App\Enums\State;
use Filament\Tables;
use App\Enums\Status;
use App\Enums\Enabled;
use App\Models\Process;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\TableWidget as BaseWidget;

class ProcessWidget extends BaseWidget
{
    protected static ?string $heading = 'Tiempo de Entrega de Procesos';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        return Process::query()
            ->whereHas('transaction.profiles', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('completed', false)
            ->whereHas('transaction', function ($query) {
                $query->where('enabled', 1);
            })
            ->whereDate('delivery_date', '>=', now())
            ->orderBy('delivery_date');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('transaction.id')
                ->label('# Opción'),
            Tables\Columns\TextColumn::make('id')
                ->label('# Proceso'),
            Tables\Columns\IconColumn::make('transaction.enabled')
                    ->label('Habilitado')
                    ->icon(fn ($state) => Enabled::from($state)->getIcon())
                    ->color(fn ($state) => Enabled::from($state)->getColor()),
            Tables\Columns\TextColumn::make('stage.stage')
                ->label("Etapa"),

            Tables\Columns\TextColumn::make('delivery_date')
                ->label('Entrega')
                ->date(),
            Tables\Columns\TextColumn::make('state')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => State::tryFrom($state)?->getLabel())
                    ->badge()
                    ->color(fn ($state) => State::tryFrom($state)?->getColor()),
            Tables\Columns\TextColumn::make('tiempo_restante')
                ->label('Tiempo restante')
                ->getStateUsing(function (Process $record) {
                    $now = now();
                    $end = $record->delivery_date;

                    if ($now->gt($end)) {
                        return 'Vencido';
                    }

                    $diffInMinutes = $now->diffInMinutes($end);
                    $days = intdiv($diffInMinutes, 1440);
                    $hours = intdiv($diffInMinutes % 1440, 60);
                    $minutes = $diffInMinutes % 60;

                    return "{$days}d {$hours}h {$minutes}m";
                }),
            Tables\Columns\TextColumn::make('Acciones')
                ->default('Ver')
                ->url(fn (Process $record) => route('filament.student.resources.transactions.edit', ['record' => $record->transaction_id]))
                ->openUrlInNewTab()
                ->icon('heroicon-m-arrow-top-right-on-square'),
        ];
    }
}
