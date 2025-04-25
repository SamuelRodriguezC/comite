<?php

namespace App\Filament\Student\Resources\ProcessResource\Pages;

use App\Filament\Student\Resources\ProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProcess extends CreateRecord
{
    protected static string $resource = ProcessResource::class;

    // Pasar el campo “state” por defecto en 3 = "Pendiente"
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['state'] = 3;
        return $data;
    }
}
