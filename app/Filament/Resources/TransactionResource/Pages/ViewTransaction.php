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
