<?php

namespace App\Filament\Evaluator\Resources\OptionResource\Pages;

use App\Filament\Evaluator\Resources\OptionResource;
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
