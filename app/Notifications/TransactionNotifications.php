<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TransactionNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    public static function sendTransactionAssigned(User $user, Transaction $transaction): void
    {
        Notification::make()
            ->title('Nueva Opción Asignada')
            ->body("Se te ha asignado la Opción de Grado #{$transaction->id}.")
            ->icon('heroicon-o-academic-cap')
            ->success()
            ->sendToDatabase($user);
    }

}
