<?php

namespace App\Filament\Advisor\Resources\OptionResource\Pages;

use App\Filament\Advisor\Resources\OptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOption extends EditRecord
{
    protected static string $resource = OptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
