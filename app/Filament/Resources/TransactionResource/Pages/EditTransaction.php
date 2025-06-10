<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TransactionResource;
use App\Notifications\TransactionNotifications;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function afterSave(): void
    {
        // Solo continuar si el campo "enabled" ha cambiado
        if (!$this->record->wasChanged('enabled')) {
            return;
        }

        $currentUserId = Auth::user()->id;

        foreach ($this->record->load('profiles.user')->profiles as $profile) {
            //Enviar notificación a todos los perfiles menos al usuario en sesión = Coordinador
            if ($profile->user && $profile->user->id !== $currentUserId) {
                $this->record->enabled === 1 // Habilitada
                    ? TransactionNotifications::sendTransactionEnabled($profile->user, $this->record)
                    : TransactionNotifications::sendTransactionDisabled($profile->user, $this->record);
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('view')
                ->label('Visualizar acta')
                ->icon('heroicon-o-eye')
                ->url(function ($record) {
                    $filename = basename($record->certificate?->acta);
                    return $filename ? route('certificate.view', ['file' => $filename]) : null;
                })
                ->hidden(fn($record) => empty($record->certificate?->acta))
                ->openUrlInNewTab(),

            Actions\Action::make('download')
                ->label('Descargar acta')
                ->icon('heroicon-o-folder-arrow-down')
                ->url(function ($record) {
                    $filename = basename($record->certificate?->acta);
                    return $filename ? route('certificate.download', ['file' => $filename]) : null;
                })
                ->hidden(fn($record) => empty($record->certificate?->acta))
                ->openUrlInNewTab(),

        Action::make('Generar PDF')
            ->color('success')
            ->label('Certificar')
            ->icon('heroicon-o-document-check')
            ->form([
                \Filament\Forms\Components\Textarea::make('observation')
                    ->label('Observaciones del Coordinador')
                    ->required()
                    ->maxLength(150)
                    ->rows(4),
            ])
            ->action(function (array $data, $record) {
                // Guardar la observación antes de redirigir
                session()->flash('certificate_observation', $data['observation']);

                return redirect()->route('certificate.pdf', $record->id);
            })
            ->modalHeading('¿Certificar Estudiante/s?')
            ->modalDescription('¿Estas seguro de certificar al/los estudiante/s? Esta acción no se puede revertir. Asegúrate que se cumplan todos los requisitos.')
            ->modalSubmitActionLabel('Sí, certificar')
            ->hidden(fn($record) => !empty($record->certificate?->acta)),

        ];
    }
}
