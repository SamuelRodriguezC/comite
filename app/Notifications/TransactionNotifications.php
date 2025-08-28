<?php

namespace App\Notifications;

use App\Models\User;
use App\Enums\Status;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TransactionNotifications extends Notification
{
    use Queueable;

    /**
     * Crear una nueva instancia de la clase.
     */
    public function __construct()
    {
        //
    }


    /**
     * Notifica a un usuario cuando se le asigna una nueva transacción.
     *
     * @param User $user El usuario al que se le asigna la transacción.
     * @param Transaction $transaction La transacción asignada.
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
     *
     * @param User $studentUser El estudiante dueño de la transacción.
     * @param User $assignedUser El usuario que fue asignado como rol.
     * @param string $roleName Nombre del rol asignado (ejemplo: "asesor", "evaluador").
     * @param Transaction $transaction La transacción a la que pertenece.
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
     * Notifica a un usuario cuando su transacción ha sido habilitada.
     *
     * @param User $user El usuario relacionado con la transacción.
     * @param Transaction $transaction La transacción habilitada.
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
     * Notifica a un usuario cuando su transacción ha sido deshabilitada.
     *
     * @param User $user El usuario relacionado con la transacción.
     * @param Transaction $transaction La transacción deshabilitada.
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
     * Notifica a todos los usuarios asociados cuando cambia el estado de una transacción.
     *
     * @param Transaction $transaction La transacción cuyo estado cambió.
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


    /**
     * Notifica a todos los coordinadores cuando se solicita certificación.
     *
     * @param Transaction $transaction
     */
    public static function sendCertificationRequested(Transaction $transaction): void
    {
        // Obtiene todos los usuarios con rol 'coordinador'
        $coordinators = Role::findByName('coordinador')->users;

        foreach ($coordinators as $user) {
            Notification::make()
                ->title('Solicitud de Certificación')
                ->body("Se ha realizado una solicitud de certificación para la Opción de Grado #{$transaction->id}.")
                ->icon('heroicon-o-clipboard-document-check')
                ->success()
                ->sendToDatabase($user);
        }
    }


    /**
     * Notifica a un asesor cuando se le realiza una certificación.
     *
     * @param User $user El asesor asignado.
     * @param Transaction $transaction La transacción certificada.
     */
    public static function sendAdvisorCertification(User $user, Transaction $transaction): void
    {
        Notification::make()
            ->title('Certificación Realizada')
            ->body("Se ha generado su certificación para la Opción de Grado #{$transaction->id}.")
            ->icon('heroicon-o-clipboard-document-check')
            ->success()
            ->sendToDatabase($user);
    }

}
