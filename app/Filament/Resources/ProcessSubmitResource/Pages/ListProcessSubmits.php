<?php

namespace App\Filament\Resources\ProcessSubmitResource\Pages;

use App\Filament\Resources\ProcessSubmitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcessSubmits extends ListRecords
{
    protected static string $resource = ProcessSubmitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
