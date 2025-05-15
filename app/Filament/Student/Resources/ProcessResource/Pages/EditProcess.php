<?php

namespace App\Filament\Student\Resources\ProcessResource\Pages;

use App\Filament\Student\Resources\ProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcess extends EditRecord
{
    protected static string $resource = ProcessResource::class;

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
