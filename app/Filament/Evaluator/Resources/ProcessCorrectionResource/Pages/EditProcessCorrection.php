<?php

namespace App\Filament\Evaluator\Resources\ProcessCorrectionResource\Pages;

use App\Filament\Evaluator\Resources\ProcessCorrectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessCorrection extends EditRecord
{
    protected static string $resource = ProcessCorrectionResource::class;

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
