<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TransactionResource;

class EditTransaction extends EditRecord
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
            ->url(function ($record) {
                if ($record->certificate?->acta) {
                    return route('certificate.view', ['file' => basename($record->certificate->acta)]);
                }
                return route('certificate.pdf', $record->id);
            })
            ->hidden(fn($record) => !empty($record->certificate?->acta))
            ->openUrlInNewTab(),
        ];
    }
}
