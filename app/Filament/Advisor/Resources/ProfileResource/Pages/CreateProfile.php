<?php

namespace App\Filament\Advisor\Resources\ProfileResource\Pages;

use App\Filament\Advisor\Resources\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProfile extends CreateRecord
{
    protected static string $resource = ProfileResource::class;
}
