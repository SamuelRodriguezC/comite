<?php

namespace App\Filament\Evaluator\Resources\ProcessOthersResource\Pages;

use App\Filament\Evaluator\Resources\ProcessOthersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessOthers extends EditRecord
{
    protected static string $resource = ProcessOthersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            //Actions\DeleteAction::make(),
        ];
    }
}
