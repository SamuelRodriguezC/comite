<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileNotifications extends Notification
{
    use Queueable;

    /**
     * Crear una nueva instancia.
     */
    public function __construct()
    {
        //
    }


}
