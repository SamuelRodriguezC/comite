<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\Pages;

use App\Enums\Status;
use Filament\Actions;
use App\Enums\Enabled;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Evaluator\Resources\TransactionResource;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    public function getTabs(): array
    {
        $profileId = Auth::user()->profiles->id;

        // Define estados con su etiqueta
        $states = [
            'En Progreso' => \App\Enums\Status::ENPROGRESO,
            'Completado' => \App\Enums\Status::COMPLETADO,
            'Por Certificar' => \App\Enums\Status::PORCERTIFICAR,
            'Certificado' => \App\Enums\Status::CERTIFICADO,
            'Cancelado' => \App\Enums\Status::CANCELADO,
        ];

        $tabs = [];

        // Tab general para todos los estados
        $tabs['all'] = Tab::make('All Options')
            ->label('Todos los Estados');

        // Crear tabs dinámicamente según los estados definidos
        foreach ($states as $label => $status) {
            $tabs[$label] = Tab::make($label)
                ->label($label) // Título del tab

                // Filtrar registros por el estado correspondiente
                ->modifyQueryUsing(fn($query) => $query->where('status', $status))

                // Mostrar la cantidad de transacciones del evaluador autenticado en ese estado
                ->badge(function () use ($profileId, $status) {
                    return \App\Models\ProfileTransaction::where('profile_id', $profileId)
                        ->whereHas('role', fn($q) => $q->where('id', 3)) // Rol Evaluador
                        ->whereHas('transaction', fn($q) => $q->where('status', $status))
                        ->count();
                });
        }

        return $tabs;
    }

    protected function getHeaderWidgets(): array
    {
        return [
             \App\Filament\Evaluator\Resources\TransactionResource\Widgets\TransactionStat::class,
        ];
    }
}
