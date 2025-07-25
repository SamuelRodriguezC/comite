<?php

namespace App\Filament\Evaluator\Resources\ProcessSubmitResource\Pages;

use App\Filament\Evaluator\Resources\ProcessSubmitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcessSubmit extends ViewRecord
{
    protected static string $resource = ProcessSubmitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Botón para ver el requerimiento cargado
            Actions\Action::make('view')
                ->label('Vizualizar requerimiento')
                ->icon('heroicon-o-eye') // incorpora icono
                ->url(
                    fn ($record) => route('file.view', ['file' => basename($record->requirement)]) //Redirigir a la ruta de visualización el archivo sin mostar la ruta completa del archivo (Solo su nombre)
                )
                ->hidden(fn ($record) => empty(trim($record->requirement))) // Oculta el botón si no hay requerimiento
                ->openUrlInNewTab(), // Abre la vista en una nueva pestaña

            //Botón para descargar el requerimiento
            Actions\Action::make('download')
                ->icon('heroicon-o-folder-arrow-down') // Icono de descarga (opcional)
                ->label('Descargar requerimiento')
                ->url(
                    fn ($record) => route('file.download', ['file' => basename($record->requirement)])
                )
                ->hidden(fn ($record) => empty(trim($record->requirement)))
                ->openUrlInNewTab(), // Abre en una nueva pestaña

            // Opción para editar deshabilitada
            //Actions\EditAction::make(),
        ];
    }
}
