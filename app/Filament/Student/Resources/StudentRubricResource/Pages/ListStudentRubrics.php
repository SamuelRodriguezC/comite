<?php

namespace App\Filament\Student\Resources\StudentRubricResource\Pages;

use App\Filament\Student\Resources\StudentRubricResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentRubrics extends ListRecords
{
    protected static string $resource = StudentRubricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
