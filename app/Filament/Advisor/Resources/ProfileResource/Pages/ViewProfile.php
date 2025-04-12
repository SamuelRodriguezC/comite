<?php

namespace App\Filament\Advisor\Resources\ProfileResource\Pages;

use App\Filament\Advisor\Resources\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProfile extends ViewRecord
{
    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
