<?php

namespace App\Filament\Advisor\Resources\OptionResource\Pages;

use App\Filament\Advisor\Resources\OptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOption extends ViewRecord
{
    protected static string $resource = OptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
