<?php

namespace App\Filament\Advisor\Resources\ProcessSubmitResource\Pages;

use App\Filament\Advisor\Resources\ProcessSubmitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessSubmit extends EditRecord
{
    protected static string $resource = ProcessSubmitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            //Actions\DeleteAction::make(),
        ];
    }
}
