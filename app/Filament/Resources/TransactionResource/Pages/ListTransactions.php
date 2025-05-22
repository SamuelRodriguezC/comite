<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use App\Enums\Enabled;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\TransactionResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListTransactions extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TransactionResource::class;

    // Función para filtrar las opciones de grado por nivel universitario
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
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\TransactionResource\Widgets\TransactionStat::class,
        ];
    }


}
