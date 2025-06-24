<?php

namespace App\Filament\Evaluator\Resources\OptionResource\Pages;

use App\Filament\Evaluator\Resources\OptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOption extends EditRecord
{
    protected static string $resource = OptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Opción para Eliminar Deshabilitada
            //Actions\DeleteAction::make(),
        ];
    }
}
