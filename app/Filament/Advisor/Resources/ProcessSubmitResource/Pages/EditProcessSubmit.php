<?php

namespace App\Filament\Advisor\Resources\ProcessSubmitResource\Pages;

use App\Filament\Advisor\Resources\ProcessSubmitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessSubmit extends EditRecord
{
    protected static string $resource = ProcessSubmitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Acción de eliminación deshabilitada
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
