<?php

namespace App\Filament\Student\Resources\ProcessResource\Pages;

use App\Filament\Student\Resources\ProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcess extends EditRecord
{
    protected static string $resource = ProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
