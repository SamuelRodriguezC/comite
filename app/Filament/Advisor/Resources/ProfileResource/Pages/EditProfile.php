<?php

namespace App\Filament\Advisor\Resources\ProfileResource\Pages;

use App\Filament\Advisor\Resources\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfile extends EditRecord
{
    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Acción de eliminación deshabilitada
            //Actions\DeleteAction::make(),
        ];
    }
}
