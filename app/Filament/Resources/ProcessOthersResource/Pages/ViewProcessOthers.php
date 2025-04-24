<?php

namespace App\Filament\Resources\ProcessOthersResource\Pages;

use App\Filament\Resources\ProcessOthersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcessOthers extends ViewRecord
{
    protected static string $resource = ProcessOthersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
