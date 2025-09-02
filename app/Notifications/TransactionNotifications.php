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
use Illuminate\Notifications\Notification as BaseNotification;

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


    // -----------------------------------------------------------------------------------------------------------
    /**
     * Notifica a un usuario cuando se le asigna una nueva transacción.
    *
    * @param User $user El usuario al que se le asigna la transacción.
    * @param Transaction $transaction La transacción asignada.
    * @param int $roleId El ID del rol asignado.
    */
    public static function sendTransactionAssigned(User $user, Transaction $transaction, int $roleId): void
    {
        $roleName = \App\Models\Role::find($roleId)?->name ?? 'Rol no encontrado';

        // Obtener el perfil asociado al usuario
        $profile = $user->profiles; // relación hasOne
        $profileName = $profile?->full_name ?? $user->name; // usa accessor getFullNameAttribute()

        // Notificación en Filament
        Notification::make()
            ->title('Nueva Opción Asignada')
            ->body("{$profileName}, se te ha asignado la Opción de Grado #{$transaction->id} como {$roleName}.")
            ->icon('heroicon-o-academic-cap')
            ->success()
            ->sendToDatabase($user);

        // Notificación por correo
        $user->notify(new class($transaction, $roleName, $profileName) extends BaseNotification {
            public Transaction $transaction;
            public string $roleName;
            public string $profileName;

            public function __construct(Transaction $transaction, string $roleName, string $profileName)
            {
                $this->transaction = $transaction;
                $this->roleName = $roleName;
                $this->profileName = $profileName;
            }

            public function via($notifiable): array
            {
                return ['mail'];
            }

            public function toMail($notifiable): MailMessage
            {
                return (new MailMessage)
                    ->subject('Nueva Opción de Grado Asignada')
                    ->greeting("Hola {$this->profileName},")
                    ->line("Se te ha asignado la Opción de Grado #{$this->transaction->id} como {$this->roleName}.")
                    ->action('Ver Detalles', url("/login"))
                    ->line('Por favor revisa los detalles de tu opción en el sistema.');
            }
        });
    }

    // -----------------------------------------------------------------------------------------------------------
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
        // Obtener perfil del usuario asignado
        $profile = $assignedUser->profiles; // relación hasOne
        $assignedProfileName = $profile?->full_name ?? "{$assignedUser->name} {$assignedUser->last_name}";

        // Notificación en Filament (base de datos)
        Notification::make()
            ->title("Nuevo {$roleName} asignado")
            ->body("Se ha asignado el {$roleName} {$assignedProfileName} para tu opción de grado #{$transaction->id}.")
            ->icon('heroicon-o-user-plus')
            ->success()
            ->sendToDatabase($studentUser);

        // Notificación por correo
        $studentUser->notify(new class($assignedProfileName, $roleName, $transaction) extends BaseNotification {
            public string $assignedProfileName;
            public string $roleName;
            public Transaction $transaction;

            public function __construct(string $assignedProfileName, string $roleName, Transaction $transaction)
            {
                $this->assignedProfileName = $assignedProfileName;
                $this->roleName = $roleName;
                $this->transaction = $transaction;
            }

            public function via($notifiable): array
            {
                return ['mail'];
            }

            public function toMail($notifiable): MailMessage
            {
                $studentProfileName = $notifiable->profiles?->full_name ?? $notifiable->name;

                return (new MailMessage)
                    ->subject("Nuevo {$this->roleName} asignado")
                    ->greeting("Hola {$studentProfileName},")
                    ->line("Se te ha asignado el {$this->roleName} **{$this->assignedProfileName}** para tu opción de grado #{$this->transaction->id}.")
                    ->action('Ver Detalles', url("/transactions/{$this->transaction->id}"))
                    ->line('Por favor revisa los detalles en el sistema.');
            }
        });
    }


    // ----------------------------------------------------------------------------------------------------------
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
         // Notificación por correo
        $user->notify(new class($transaction) extends \Illuminate\Notifications\Notification {
            public Transaction $transaction;

            public function __construct(Transaction $transaction)
            {
                $this->transaction = $transaction;
            }

            public function via($notifiable): array
            {
                return ['mail'];
            }

            public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
            {
                $greetingName = $notifiable->profiles?->full_name ?? $notifiable->name;

                return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Tu Opción de Grado ha sido habilitada')
                    ->greeting("Hola {$greetingName},")
                    ->line("La Opción de Grado #{$this->transaction->id} ha sido habilitada.")
                    ->action('Ver Detalles', url("/transactions/{$this->transaction->id}"))
                    ->line('Ya puedes continuar con el proceso en el sistema.');
            }
        });

    }

    // -----------------------------------------------------------------------------------------------------------
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
        // Notificación por correo
        $user->notify(new class($transaction) extends \Illuminate\Notifications\Notification {
            public Transaction $transaction;

            public function __construct(Transaction $transaction)
            {
                $this->transaction = $transaction;
            }

            public function via($notifiable): array
            {
                return ['mail'];
            }

            public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
            {
                $greetingName = $notifiable->profiles && $notifiable->profiles->full_name
                    ? $notifiable->profiles->full_name
                    : $notifiable->name;

                return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Tu Opción de Grado ha sido deshabilitada')
                    ->greeting("Hola {$greetingName},")
                    ->line("La Opción de Grado #{$this->transaction->id} ha sido deshabilitada.")
                    ->action('Ver Detalles', url("/login"))
                    ->line('Por ahora solo podrás ver el conenido de tu opción de grado pero no podrás realizar ninguna acción');
            }
        });
    }


    // -----------------------------------------------------------------------------------------------------------
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
            // Notificación en Filament
            Notification::make()
                ->title("Estado actualizado")
                ->body("La Opción de Grado #{$transaction->id} ha cambiado su estado a *{$estado}*.")
                ->icon($icon)
                ->{$color}()
                ->sendToDatabase($user);

            // Notificación por correo
                $user->notify(new class($transaction, $estado) extends \Illuminate\Notifications\Notification {
                    public Transaction $transaction;
                    public string $estado;

                    public function __construct(Transaction $transaction, string $estado)
                    {
                        $this->transaction = $transaction;
                        $this->estado = $estado;
                    }

                    public function via($notifiable): array
                    {
                        return ['mail'];
                    }

                    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
                    {
                        $greetingName = $notifiable->profiles?->full_name ?? $notifiable->name;

                        return (new \Illuminate\Notifications\Messages\MailMessage)
                            ->subject("Cambio de estado en tu Opción de Grado")
                            ->greeting("Hola {$greetingName},")
                            ->line("La Opción de Grado #{$this->transaction->id} ha cambiado su estado a **{$this->estado}**.")
                            ->action('Ver Detalles', url("/transactions/{$this->transaction->id}"))
                            ->line('Por favor revisa los detalles en el sistema.');
                    }
                });
            }
        }
    }


    // -----------------------------------------------------------------------------------------------------------
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
             // Notificación por correo
                $user->notify(new class($transaction) extends \Illuminate\Notifications\Notification {
                    public Transaction $transaction;

                    public function __construct(Transaction $transaction)
                    {
                        $this->transaction = $transaction;
                    }

                    public function via($notifiable): array
                    {
                        return ['mail'];
                    }

                    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
                    {
                        $greetingName = $notifiable->profiles?->full_name ?? $notifiable->name;

                        return (new \Illuminate\Notifications\Messages\MailMessage)
                            ->subject('Nueva solicitud de certificación')
                            ->greeting("Hola {$greetingName},")
                            ->line("Se ha solicitado la certificación de la Opción de Grado #{$this->transaction->id}.")
                            ->action('Ver Detalles', url("/transactions/{$this->transaction->id}"))
                            ->line('Por favor ingresa al sistema para revisar la solicitud.');
                    }
                });
        }
    }


    // -----------------------------------------------------------------------------------------------------------
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
        // Notificación por correo
        $user->notify(new class($transaction) extends \Illuminate\Notifications\Notification {
            public Transaction $transaction;

            public function __construct(Transaction $transaction)
            {
                $this->transaction = $transaction;
            }

            public function via($notifiable): array
            {
                return ['mail'];
            }

            public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
            {
                $greetingName = $notifiable->profiles?->full_name ?? $notifiable->name;

                return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Se ha generado su certificación')
                    ->greeting("Hola {$greetingName},")
                    ->line("Se ha generado su certificación para la Opción de Grado #{$this->transaction->id}.")
                    ->action('Ver Detalles', url("/transactions/{$this->transaction->id}"))
                    ->line('Por favor revisa el sistema para ver más detalles.');
            }
        });
    }


    // -----------------------------------------------------------------------------------------------------------
    /**
     * Notifica a los estudiantes cuando se les genera una certificación.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $students
     * @param Transaction $transaction
     */
    public static function sendCertificationStudents($students, Transaction $transaction): void
    {
        foreach ($students as $student) {
            $user = $student->user; // relación con el usuario

            if ($user) {
                // Notificación en Filament
                Notification::make()
                    ->title('Certificación Realizada')
                    ->body("Se ha generado tu certificación para la Opción de Grado #{$transaction->id}.")
                    ->icon('heroicon-o-clipboard-document-check')
                    ->success()
                    ->sendToDatabase($user);

                // Notificación por correo
                $user->notify(new class($transaction) extends \Illuminate\Notifications\Notification {
                    public Transaction $transaction;

                    public function __construct(Transaction $transaction)
                    {
                        $this->transaction = $transaction;
                    }

                    public function via($notifiable): array
                    {
                        return ['mail'];
                    }

                    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
                    {
                        $greetingName = $notifiable->profiles?->full_name ?? $notifiable->name;

                        return (new \Illuminate\Notifications\Messages\MailMessage)
                            ->subject('Certificación generada')
                            ->greeting("Hola {$greetingName},")
                            ->line("¡Felicidades! se ha generado tu certificación para la Opción de Grado #{$this->transaction->id}.")
                            ->action('Ver Detalles', url("/transactions/{$this->transaction->id}"))
                            ->line('Por favor revisa el sistema para ver más detalles.');
                    }
                });
            }
        }
    }

}
