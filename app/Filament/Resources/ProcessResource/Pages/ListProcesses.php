<?php

namespace App\Filament\Resources\ProcessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProcessResource;
use Filament\Resources\Pages\ListRecords\Tab;

class ListProcesses extends ListRecords
{
    protected static string $resource = ProcessResource::class;

        // Esta función permite generar pestañas para filtrar las consultas de la tabla
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todos')
                ->label('Todos los procesos'),

            'solicitud' => Tab::make('Solicitud')
                ->label('Solicitudes')
                ->modifyQueryUsing(fn ($query) => $query->where('stage_id', '1')),

            'proceso' => Tab::make('Proceso')
                ->label('1a Entrega')
                ->modifyQueryUsing(fn ($query) => $query->where('stage_id', '2')),

            'primera_correccion' => Tab::make('Primera corrección')
                ->label('1a Corrección')
                ->modifyQueryUsing(fn ($query) => $query->where('stage_id', '3')),

            'segunda_correccion' => Tab::make('Segunda corrección')
                ->label('2a Corrección')
                ->modifyQueryUsing(fn ($query) => $query->where('stage_id', '4')),

            'finalizado' => Tab::make('Finalizado')
                ->label('Finalizados')
                ->modifyQueryUsing(fn ($query) => $query->where('stage_id', '5')),

            'cancelado' => Tab::make('Cancelado')
                ->label('Cancelados')
                ->modifyQueryUsing(fn ($query) => $query->where('stage_id', '6')),

            'aplazado' => Tab::make('Aplazado')
                ->label('Aplazados')
                ->modifyQueryUsing(fn ($query) => $query->where('stage_id', '7')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
