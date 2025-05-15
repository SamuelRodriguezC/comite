<?php

namespace App\Filament\Evaluator\Resources\ProcessSubmitResource\Pages;

use App\Filament\Evaluator\Resources\ProcessSubmitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessSubmit extends EditRecord
{
    protected static string $resource = ProcessSubmitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            //Actions\DeleteAction::make(),
        ];
    }
    // Evita la visualización del registro si está deshabilitado
    protected function authorizeAccess(): void
    {
        if ($this->record->transaction?->enabled === 2) {
            abort(403, 'Acceso denegado. Esta transacción está deshabilitada.');
        }
    }
}
