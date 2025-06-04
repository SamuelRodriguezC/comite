<?php

namespace App\Notifications;

use App\Models\Process;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProcessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {

    }

    public function via($notifiable)
    {
        return ['database']; // o ['mail', 'database'] si envías correo
    }

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
