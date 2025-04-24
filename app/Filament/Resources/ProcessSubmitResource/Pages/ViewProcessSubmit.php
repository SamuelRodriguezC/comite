<?php

namespace App\Filament\Resources\ProcessSubmitResource\Pages;

use App\Filament\Resources\ProcessSubmitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcessSubmit extends ViewRecord
{
    protected static string $resource = ProcessSubmitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
