<?php

namespace App\Filament\Resources\ProcessAplicationResource\Pages;

use App\Filament\Resources\ProcessAplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessAplication extends EditRecord
{
    protected static string $resource = ProcessAplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
