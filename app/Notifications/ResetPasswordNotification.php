<?php

namespace App\Notifications;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;

/**
 * Notificación personalizada para el restablecimiento de contraseña.
 *
 * Esta clase sobrescribe el método `toMail()` de la notificación base
 * de Laravel para personalizar el contenido del correo.
 *
 * Se utiliza cuando un usuario solicita restablecer su contraseña.
 *
 * @see \Illuminate\Auth\Notifications\ResetPassword
 */
class ResetPasswordNotification extends ResetPasswordBase
{

    /**
     * Construye el mensaje de correo para el restablecimiento de contraseña.
     *
     * @param mixed $notifiable Modelo notificado (generalmente un usuario).
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablecer contraseña')
            ->line('Has solicitado restablecer tu contraseña.')
            ->action('Restablecer contraseña', $url)
            ->line('Si no hiciste esta solicitud, ignora este correo.');
    }
}
