<?php

namespace App\Filament\Resources\ProcessCorrectionResource\Pages;

use App\Filament\Resources\ProcessCorrectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcessCorrections extends ListRecords
{
    protected static string $resource = ProcessCorrectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
