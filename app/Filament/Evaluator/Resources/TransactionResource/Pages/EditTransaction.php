<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\Pages;

use App\Filament\Evaluator\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Opcion para eliminar deshabilitada
            //Actions\DeleteAction::make(),
        ];
    }

    // prohibe editar registros deshabilitados
    protected function authorizeAccess(): void
    {
        abort_if($this->record->enabled === 2, 403);
    }
}
