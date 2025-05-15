<?php

namespace App\Filament\Advisor\Resources\ProcessAplicationResource\Pages;

use App\Filament\Advisor\Resources\ProcessAplicationResource;
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
    // Evita la visualización del registro si está deshabilitado
    protected function authorizeAccess(): void
    {
        if ($this->record->transaction?->enabled === 2) {
            abort(403, 'Acceso denegado. Esta transacción está deshabilitada.');
        }
    }
}
