<?php

namespace App\Filament\Evaluator\Widgets;

use App\Enums\Enabled;
use App\Enums\Component;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Tables\Columns\IconColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LatestTransactionsEvaluator extends TableWidget
{
    protected static ?string $heading = 'Ultimas Transacciones';
    protected static ?int $sort = 4;


    protected int|string|array $columnSpan = 'full';


protected function getTableQuery(): Builder
{
    $user = Auth::user();

    return Transaction::query()
        ->whereHas('profileTransactions', function ($query) use ($user) {
            $query->where('profile_id', $user->profiles->id ?? null)
                  ->where('role_id', 3); // Evaluador
        })
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->take(10);
}

protected function getTableColumns(): array
{
    return [
         TextColumn::make('id')
                    ->label('Opciones')
                    ->numeric(),
                TextColumn::make('component')
                    ->label("Componente")
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),
                TextColumn::make('option.option')
                    ->label("Opción de grado")
                    ->words(3),
                TextColumn::make('courses')
                    ->label('Carreras')
                    ->words(4),
                    // ->searchable(),
                IconColumn::make('enabled')
                    ->label('Habilitado')
                    ->icon(fn ($state) => Enabled::from($state)->getIcon())
                    ->color(fn ($state) => Enabled::from($state)->getColor()),
                TextColumn::make('created_at')
                    ->label("Creado en")
                    ->dateTime(),
    ];
}
}



