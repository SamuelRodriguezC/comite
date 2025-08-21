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


        Action::make('certify')
            ->label('Certificar Estudiantes')
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
            ->hidden(fn ($record) => !empty($record->certificate?->acta)),
        ];
    }
}
