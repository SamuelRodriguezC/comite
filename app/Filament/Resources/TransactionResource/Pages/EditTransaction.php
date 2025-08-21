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

        // Cargar relaciones solo si no están cargadas
        $this->record->loadMissing('profiles.user');

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

        Action::make('certify')
            ->label(fn ($record) => $record->certificate ? 'Generar Nuevo' : 'Certificar Estudiantes')
            ->icon('heroicon-o-document-check')
            ->color('success')
            ->form([
                \Filament\Forms\Components\Select::make('signer_id')
                    ->label('Seleccionar Director de Investigación')
                    ->options(
                        \App\Models\Signer::query()
                            ->get()
                            ->pluck('display_name', 'id') // pluck ya devuelve [id => display_name]
                    )
                    ->required(),
            ])
            ->action(function (array $data, $record) {
                session()->put('certificate_signer_id', $data['signer_id']);

                return redirect()->route(
                    'filament.coordinator.resources.transactions.certify-students',
                    ['record' => $record->id]
                );
            })
            ->modalHeading('Seleccionar Firmador')
            ->modalSubmitActionLabel('Continuar')
        ];
    }
}
