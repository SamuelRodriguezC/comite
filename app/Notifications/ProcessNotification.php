<?php

namespace App\Notifications;

use App\Models\Process;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notificación personalizada para informar cambios de estado en un proceso.
 *
 * Esta clase aprovecha el sistema de notificaciones de Filament para
 * enviar notificaciones al canal de base de datos.
 *
 * @example
 * ProcessNotification::stateUpdated($transaction, $user, $process);
 */
class ProcessNotification extends Notification
{
    use Queueable;

    /**
     * Crea una nueva instancia de la notificación.
     */
    public function __construct()
    {

    }

    /**
     * Define los canales por los cuales se enviará la notificación.
     *
     * @param mixed $notifiable El modelo que recibirá la notificación.
     * @return array<string> Canales disponibles ('database', 'mail', etc.).
     */
    public function via($notifiable)
    {
        return ['database']; // o ['mail', 'database'] si se envía correo
    }



    /**
     * Envía una notificación cuando cambia el estado de un proceso.
     *
     * Este método estático puede ser llamado desde cualquier parte del sistema,
     * por ejemplo, desde un observer o un controlador.
     *
     * @param Transaction $transaction Transacción a la que pertenece el proceso.
     * @param mixed $notifiable Modelo que será notificado (usualmente un User o Profile).
     * @param Process $process Proceso cuyo estado ha cambiado.
     * @return void
     */
    public static function stateUpdated(Transaction $transaction, $notifiable, Process $process): void
    {
        // Obtener el label del nuevo estado usando el enum
        $stateLabel = $process->state ? \App\Enums\State::from($process->state)->getLabel() : 'Estado desconocido';

        Notification::make()
            ->title('Estado del proceso actualizado')
            ->body("El proceso de '{$process->stage->stage}' de tu transacción #{$transaction->id} ha cambiado a: {$stateLabel}.")
            ->icon('heroicon-o-arrow-path-rounded-square')
            ->success()
            ->sendToDatabase($notifiable);
    }
}
