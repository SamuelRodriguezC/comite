<?php

namespace App\Filament\Resources\ProcessResource\Pages;

use App\Filament\Resources\ProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcess extends ViewRecord
{
    protected static string $resource = ProcessResource::class;

    // Incorporación de botones para visualizar y descargar archivos de Procesos
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label('Vizualizar requerimiento')
                ->icon('heroicon-o-eye') // incorpora icono
                ->url(
                    fn ($record) => route('file.view', ['file' => basename($record->requirement)])
                )
                ->hidden(fn ($record) => empty(trim($record->requirement)))
                ->openUrlInNewTab(), // Abre la vista en una nueva pestaña

            Actions\Action::make('download')
                ->icon('heroicon-o-folder-arrow-down') // Icono de descarga (opcional)
                ->label('Descargar requerimiento')
                ->url(
                    fn ($record) => route('file.download', ['file' => basename($record->requirement)])
                )
                ->hidden(fn ($record) => empty(trim($record->requirement)))
                ->openUrlInNewTab(), // Abre en una nueva pestaña

            Actions\EditAction::make(),
        ];
    }
}
