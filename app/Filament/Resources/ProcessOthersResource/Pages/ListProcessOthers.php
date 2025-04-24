<?php

namespace App\Filament\Resources\ProcessOthersResource\Pages;

use App\Filament\Resources\ProcessOthersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcessOthers extends ListRecords
{
    protected static string $resource = ProcessOthersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
