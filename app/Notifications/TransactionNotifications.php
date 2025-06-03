<?php

namespace App\Notifications;

use App\Models\User;
use App\Enums\Status;
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
     * Notifica al estudiante cuando se le asigna un evaluador o asesor.
     */
    public static function sendRoleAssigned(User $studentUser, User $assignedUser, string $roleName, Transaction $transaction): void
    {
        Notification::make()
            ->title("Nuevo {$roleName} asignado")
            ->body("Se ha asignado el {$roleName} {$assignedUser->name} {$assignedUser->last_name} para tu opción de grado #{$transaction->id}.")
            ->icon('heroicon-o-user-plus')
            ->success()
            ->sendToDatabase($studentUser);
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

    /**
     * Notifica a todos los usuarios asociados cuando cambia el estado de la transacción.
     */
    public static function sendStatusChanged(Transaction $transaction): void
    {
        $statusEnum = Status::tryFrom($transaction->status); // Convierte el número a enum
        $estado = $statusEnum?->getLabel(); // Obtiene el nombre legible

        $icon = 'heroicon-o-information-circle';
        $color = 'info';


        foreach ($transaction->profileTransactions as $profileTransaction) {
            $user = $profileTransaction->profile->user;

            if ($user) {
                Notification::make()
                    ->title("Estado actualizado")
                    ->body("La Opción de Grado #{$transaction->id} ha cambiado su estado a *{$estado}*.")
                    ->icon($icon)
                    ->{$color}()
                    ->sendToDatabase($user);
            }
        }
    }


}
