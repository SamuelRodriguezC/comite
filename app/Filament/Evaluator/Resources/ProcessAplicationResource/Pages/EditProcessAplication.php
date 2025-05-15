<?php

namespace App\Filament\Evaluator\Resources\ProcessAplicationResource\Pages;

use App\Filament\Evaluator\Resources\ProcessAplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessAplication extends EditRecord
{
    protected static string $resource = ProcessAplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            //Actions\DeleteAction::make(),
        ];
    }
    // prohibe editar registros desahilitados
    protected function authorizeAccess(): void
    {
        abort_if($this->record->enabled === 2, 403);
    }
}
