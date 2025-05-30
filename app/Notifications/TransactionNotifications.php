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
        // Obtener el panel según el rol del usuario (puedes adaptarlo)
        $panel = match (true) {
            $user->hasRole(1) => 'student',
            $user->hasRole(3) => 'evaluator',
            $user->hasRole(2) => 'advisor',
            default => 'coordinator',
        };

        // Construir la URL según el panel
        $url = route("filament.{$panel}.resources.transactions.view", ['record' => $transaction->id]);

        Notification::make()
            ->title('Nueva Opción de Grado Asignada')
            ->body("Se te ha asignado la transacción #{$transaction->id}.")
            ->icon('heroicon-o-academic-cap')
            ->success()
            ->actions([
                Action::make('Ver transacción')
                    ->label('Ver transacción')
                    ->button()
                    ->url($url),
            ])
            ->sendToDatabase($user);
    }

}
