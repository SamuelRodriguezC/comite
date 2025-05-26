<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\Status;
use Filament\Actions;
use App\Enums\Enabled;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\TransactionResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListTransactions extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = TransactionResource::class;

    // Función para filtrar las opciones de grado por nivel universitario
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Options')
                ->label('Todos los Estados'),
            'En Progreso' => Tab::make('En Progreso')
                ->label('En Progreso')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::ENPROGRESO);
            })->badge(\App\Models\Transaction::where('status', Status::ENPROGRESO)->count()),
            'Completado' => Tab::make('Completado')
                ->label('Completado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::COMPLETADO);
            })->badge(\App\Models\Transaction::where('status', Status::COMPLETADO)->count()),
            'Por Certificar' => Tab::make('Por Certificar')
                ->label('Por Certificar')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::PORCERTIFICAR);
            })->badge(\App\Models\Transaction::where('status', Status::PORCERTIFICAR)->count()),
            'Certificado' => Tab::make('Certificado')
                ->label('Certificado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::CERTIFICADO);
            })->badge(\App\Models\Transaction::where('status', Status::CERTIFICADO)->count()),
            'Cancelado' => Tab::make('Cancelado')
                ->label('Cancelado')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Status::CANCELADO);
            })->badge(\App\Models\Transaction::where('status', Status::CANCELADO)->count()),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\TransactionResource\Widgets\TransactionStat::class,
        ];
    }


}
