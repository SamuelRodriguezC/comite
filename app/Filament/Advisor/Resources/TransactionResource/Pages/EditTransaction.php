<?php

namespace App\Filament\Advisor\Resources\TransactionResource\Pages;

use App\Filament\Advisor\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

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
