<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\Pages;

use App\Filament\Evaluator\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Botón para ver el certificado cargado
            Actions\Action::make('view')
                ->label('Visualizar acta')
                ->icon('heroicon-o-eye')  // incorpora icono
                ->url(function ($record) {
                    $filename = basename($record->certificate?->acta);
                    return $filename ? route('certificate.view', ['file' => $filename]) : null; //Redirigir a la ruta de visualización el archivo sin mostar la ruta completa del archivo (Solo su nombre)
                })
                ->hidden(fn($record) => empty($record->certificate?->acta)) // Oculta el botón si no hay requerimiento
                ->openUrlInNewTab(),// Abre la vista en una nueva pestaña

            //Botón para descargar el requerimiento
            Actions\Action::make('download')
                ->label('Descargar acta')
                ->icon('heroicon-o-folder-arrow-down')
                ->url(function ($record) {
                    $filename = basename($record->certificate?->acta);
                    return $filename ? route('certificate.download', ['file' => $filename]) : null;
                })
                ->hidden(fn($record) => empty($record->certificate?->acta))
                ->openUrlInNewTab(),

            // Opción para editar deshabilitada
            //Actions\EditAction::make(),
        ];
    }
}
