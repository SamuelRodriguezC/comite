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
                    })->badge(\App\Models\Process::where('state', State::PENDIENTE)->whereIn('stage_id', [3, 4])->count()),
                'Entregado' => Tab::make('Entregado')
                    ->label('Entregado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::ENTREGADO);
                    })->badge(\App\Models\Process::where('state', State::ENTREGADO)->whereIn('stage_id', [3, 4])->count()),
                'Aprobado' => Tab::make('Aprobado')
                    ->label('Aprobado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::APROBADO);
                    })->badge(\App\Models\Process::where('state', State::APROBADO)->whereIn('stage_id', [3, 4])->count()),
                'Improbado' => Tab::make('Improbado')
                    ->label('Improbado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::IMPROBADO);
                    })->badge(\App\Models\Process::where('state', State::IMPROBADO)->whereIn('stage_id', [3, 4])->count()),
                'Aplazado' => Tab::make('Aplazado')
                    ->label('Aplazado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::APLAZADO);
                    })->badge(\App\Models\Process::where('state', State::APLAZADO)->whereIn('stage_id', [3, 4])->count()),
                'Cancelado' => Tab::make('Cancelado')
                    ->label('Cancelado')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::CANCELADO);
                    })->badge(\App\Models\Process::where('state', State::CANCELADO)->whereIn('stage_id', [3, 4])->count()),
                'Vencido' => Tab::make('Vencido')
                    ->label('Vencido')
                    ->modifyQueryUsing(function ($query) {
                        return $query->where('state', State::VENCIDO);
                    })->badge(\App\Models\Process::where('state', State::VENCIDO)->whereIn('stage_id', [3, 4])->count()),
            ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
