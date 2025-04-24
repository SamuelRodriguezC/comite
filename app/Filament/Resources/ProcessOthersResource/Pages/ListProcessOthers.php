<?php

namespace App\Filament\Resources\ProcessOthersResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\ProcessOthersResource;

class ListProcessOthers extends ListRecords
{
    protected static string $resource = ProcessOthersResource::class;

    // Función para filtrar las opciones de grado por nivel universitario
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Options')
                ->label('Todos los estados'),
            'Finalizado' => Tab::make('Finalizado')
                ->label('Finalizado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('stage_id', '5');
                }),
            'Cancelado' => Tab::make('Cancelado')
                ->label('Cancelado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('stage_id', '6');
                }),
            'Aplazado' => Tab::make('Aplazado')
                ->label('Aplazado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('stage_id', '7');
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
