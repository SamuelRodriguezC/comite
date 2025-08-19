<?php

namespace App\Filament\Advisor\Resources\TransactionResource\Pages;

use App\Filament\Advisor\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Enums\Status;


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

    // Evita que se vuelva a enviar si ya está bloqueado en BD
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (in_array($this->record->status, [
            Status::CERTIFICADO->value,
            Status::PORCERTIFICAR->value,
            Status::CANCELADO->value,
        ])) {
            unset($data['status']); // no intentes actualizar 'status'
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Fuerza re-render del componente Livewire (sin recargar página)
        $this->dispatch('$refresh');
        // Opcional: también puedes volver a llenar el form desde el record.
        // $this->fillForm();
    }
}
