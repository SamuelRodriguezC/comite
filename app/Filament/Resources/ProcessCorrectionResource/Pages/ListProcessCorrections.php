<?php

namespace App\Filament\Resources\ProcessCorrectionResource\Pages;

use App\Enums\State;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\ProcessCorrectionResource;

class ListProcessCorrections extends ListRecords
{
    protected static string $resource = ProcessCorrectionResource::class;

    // Función para filtrar las opciones de grado por nivel universitario
    public function getTabs(): array
    {
         return [
                'all' => Tab::make('All Options')
                    ->label('Todos los estados'),
                'Pendiente' => Tab::make('Pendiente')
                    ->label('Pendiente')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::PENDIENTE);
                    }),
                'Entregado' => Tab::make('Entregado')
                    ->label('Entregado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::ENTREGADO);
                    }),
                'Aprobado' => Tab::make('Aprobado')
                    ->label('Aprobado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::APROBADO);
                    }),
                'Improbado' => Tab::make('Improbado')
                    ->label('Improbado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::IMPROBADO);
                    }),
                'Aplazado' => Tab::make('Aplazado')
                    ->label('Aplazado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::APLAZADO);
                    }),
                'Cancelado' => Tab::make('Cancelado')
                    ->label('Cancelado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::CANCELADO);
                    }),
            ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
