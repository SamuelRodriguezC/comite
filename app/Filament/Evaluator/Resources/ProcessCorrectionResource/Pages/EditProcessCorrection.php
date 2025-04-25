<?php

namespace App\Filament\Evaluator\Resources\ProcessCorrectionResource\Pages;

use App\Filament\Evaluator\Resources\ProcessCorrectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessCorrection extends EditRecord
{
    protected static string $resource = ProcessCorrectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            //Actions\DeleteAction::make(),
        ];
    }
}
