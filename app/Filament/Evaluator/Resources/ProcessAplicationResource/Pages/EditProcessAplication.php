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
            // Opción para Eliminar Deshabilitada
            //Actions\DeleteAction::make(),
        ];
    }
    // Evita la visualización del registro si está deshabilitado
    protected function authorizeAccess(): void
    {
        if ($this->record->transaction?->enabled === 2) {
            // si la transacción está deshabilitada, se previene el acceso
            abort(403, 'Acceso denegado. Esta transacción está deshabilitada.');
        }
    }
}
