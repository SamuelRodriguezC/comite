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

    /**
     * Notifica cuando se le asigna una transacción al usuario
     */
    public static function sendTransactionAssigned(User $user, Transaction $transaction): void
    {
        Notification::make()
            ->title('Nueva Opción Asignada')
            ->body("Se te ha asignado la Opción de Grado #{$transaction->id}.")
            ->icon('heroicon-o-academic-cap')
            ->success()
            ->sendToDatabase($user);
    }


    /**
     * Notifica cuando cambia el estado de habilitación (enabled).
     */
    public static function sendTransactionEnabled(User $user, Transaction $transaction): void
    {
        Notification::make()
            ->title('Opción habilitada')
            ->body("La Opción de Grado #{$transaction->id} ha sido habilitada.")
            ->icon('heroicon-o-check-circle')
            ->success()
            ->sendToDatabase($user);
    }


    /**
     * Notifica cuando se actualiza alguna información de la transacción.
     */
    public static function sendTransactionDisabled(User $user, Transaction $transaction): void
    {
        Notification::make()
            ->title('Opción deshabilitada')
            ->body("La Opción de Grado #{$transaction->id} ha sido deshabilitada, no se podrán hacer evaluaciones o subir archivos hasta que el coodinador la habilite nuevamente.")
            ->icon('heroicon-o-x-circle')
            ->danger()
            ->sendToDatabase($user);
    }

}
