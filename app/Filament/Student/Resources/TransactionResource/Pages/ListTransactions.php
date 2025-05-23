<?php

namespace App\Filament\Student\Resources\TransactionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Student\Resources\TransactionResource;
use App\Models\Transaction;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
{
    if (!Transaction::canCreate()) {
        return [];
    }
    else {
            return [
                Actions\CreateAction::make(),
            ];
        }
    }

}
