<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Widgets\TableWidget;

class TransactionsExpiringWidget extends TableWidget
{
    protected static ?int $sort = 4;
    protected static null|string $heading = 'Tickets que no permitirán más integrantes';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Obtenemos transacciones creadas hace más de 6 horas y menos de 12 horas
          return Transaction::query()
                ->where('created_at', '>=', now()->subHours(12))
                ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('ID'),
            Tables\Columns\TextColumn::make('option.option')
                ->limit(20)
                ->label('Opción'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->label('Creada'),
            Tables\Columns\TextColumn::make('time_left')
                ->label('Tiempo para editar')
                ->getStateUsing(function (Transaction $record) {
                    $hoursPassed = $record->created_at->diffInMinutes(now()) / 60;
                    $hoursLeft = 12 - $hoursPassed;
                    if ($hoursLeft <= 0) {
                        return 'No editable';
                    }
                    $totalMinutes = (int) round($hoursLeft * 60);
                    $h = intdiv($totalMinutes, 60);
                    $m = $totalMinutes % 60;
                    return sprintf('%02d:%02d hrs', $h, $m);
                }),
        ];
    }
}
