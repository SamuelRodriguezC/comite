<?php

namespace App\Filament\Advisor\Resources\ProcessAplicationResource\Pages;

use App\Filament\Advisor\Resources\ProcessAplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcessAplication extends ViewRecord
{
    protected static string $resource = ProcessAplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label('Vizualizar requerimiento')
                ->icon('heroicon-o-eye') // incorpora icono
                // Generar URL para visualizar el archivo (quitando la ruta solo su nombre)
                ->url(
                    fn ($record) => route('file.view', ['file' => basename($record->requirement)])
                )
                // El botón se oculta si no hay requerimiento
                ->hidden(fn ($record) => empty(trim($record->requirement)))
                ->openUrlInNewTab(), // Abre la vista en una nueva pestaña

            Actions\Action::make('download')
                ->icon('heroicon-o-folder-arrow-down') // Icono de descarga (opcional)
                ->label('Descargar requerimiento')
                // Generar URL para visualizar el archivo (quitando la ruta solo su nombre)
                ->url(
                    fn ($record) => route('file.download', ['file' => basename($record->requirement)])
                )
                 // Oculta el botón si no hay un archivo disponible
                ->hidden(fn ($record) => empty(trim($record->requirement)))
                ->openUrlInNewTab(), // Abre en una nueva pestaña
        ];
    }
}
