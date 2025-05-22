<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\TransactionResource;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

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

            Actions\EditAction::make(),

            Action::make('Generar PDF')
                ->color('success')
                ->label('Certificar')
                ->requiresConfirmation()
                ->icon('heroicon-o-document-check')
                ->action(function ($record) {
                    // Lógica para redirigir al backend
                    return redirect()->route('certificate.pdf', $record->id);
                })
                 ->modalHeading('¿Certificar Estudiante/s)?')
                ->modalDescription('¿Estas seguro de certificar a el/los estudiante/s? Esta acción no se puede revertir asegurate que se cumplan todos los requisitos de certificación.')
                ->modalSubmitActionLabel('Si, Certificar')
                // ->url(function ($record) {
                //     if ($record->certificate?->acta) {
                //         return route('certificate.view', ['file' => basename($record->certificate->acta)]);
                //     }
                //     return route('certificate.pdf', $record->id);
                // }) )
                ->hidden(fn($record) => !empty($record->certificate?->acta)),
        ];
    }
}
