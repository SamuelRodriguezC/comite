<?php

namespace App\Filament\Resources\ProcessCorrectionResource\Pages;

use App\Filament\Resources\ProcessCorrectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcessCorrection extends ViewRecord
{
    protected static string $resource = ProcessCorrectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
