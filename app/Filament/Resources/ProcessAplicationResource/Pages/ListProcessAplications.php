<?php

namespace App\Filament\Resources\ProcessAplicationResource\Pages;

use App\Filament\Resources\ProcessAplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcessAplications extends ListRecords
{
    protected static string $resource = ProcessAplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
