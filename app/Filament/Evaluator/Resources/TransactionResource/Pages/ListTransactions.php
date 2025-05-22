<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\Pages;

use Filament\Actions;
use App\Enums\Enabled;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Evaluator\Resources\TransactionResource;
use Illuminate\Support\Facades\App;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Options')
                ->label('Todos los estados'),
            'habilitado' => Tab::make('habilitado')
                ->label('Habilitado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('enabled', Enabled::HABILITADO);
                }),
            'deshabilitado' => Tab::make('deshabilitado')
                ->label('No habilitado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('enabled', Enabled::DESHABILITADO);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
             \App\Filament\Evaluator\Resources\TransactionResource\Widgets\TransactionStat::class,
        ];
    }
}
