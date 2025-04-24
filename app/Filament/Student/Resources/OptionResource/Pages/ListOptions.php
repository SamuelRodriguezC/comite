<?php

namespace App\Filament\Student\Resources\OptionResource\Pages;

use App\Filament\Student\Resources\OptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOptions extends ListRecords
{
    protected static string $resource = OptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
