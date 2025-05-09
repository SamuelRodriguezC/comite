<?php

namespace App\Filament\Advisor\Resources\ProcessCorrectionResource\Pages;

use App\Filament\Advisor\Resources\ProcessCorrectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcessCorrection extends ViewRecord
{
    protected static string $resource = ProcessCorrectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label('Vizualizar requerimiento')
                ->icon('heroicon-o-eye') // incorpora icono
                ->url(
                    fn ($record) => route('file.view', ['file' => basename($record->requirement)])
                )
                ->openUrlInNewTab(), // Abre la vista en una nueva pestaña

            Actions\Action::make('download')
                ->icon('heroicon-o-folder-arrow-down') // Icono de descarga (opcional)
                ->label('Descargar requerimiento')
                ->url(
                    fn ($record) => route('file.download', ['file' => basename($record->requirement)])
                )
                ->openUrlInNewTab(), // Abre en una nueva pestaña

            //Actions\EditAction::make(),
        ];
    }
}
