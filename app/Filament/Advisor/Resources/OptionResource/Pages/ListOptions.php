<?php

namespace App\Filament\Advisor\Resources\OptionResource\Pages;

use App\Enums\Level;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Advisor\Resources\OptionResource;

class ListOptions extends ListRecords
{
    protected static string $resource = OptionResource::class;

    // Función para filtrar las opciones de grado por nivel universitario
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Options')
                ->label('Todos los niveles'),
            'pregrado' => Tab::make('pregrado')
                ->label('Pregrado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('level', Level::PREGRADO);
                }),
            'posgrado' => Tab::make('posgrado')
                ->label('Posgrado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('level', Level::POSGRADO);
                }),
        ];
    }

    //protected function getHeaderActions(): array
    //{
    //    return [
    //        Actions\CreateAction::make(),
    //    ];
    //}
}
