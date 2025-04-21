<?php

namespace App\Filament\Student\Resources\ProcessResource\Pages;

use App\Filament\Student\Resources\ProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcess extends ViewRecord
{
    protected static string $resource = ProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
