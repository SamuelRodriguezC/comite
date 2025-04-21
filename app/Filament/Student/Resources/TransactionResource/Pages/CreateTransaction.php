<?php

namespace App\Filament\Student\Resources\TransactionResource\Pages;

use App\Filament\Student\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
