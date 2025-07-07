<?php

namespace App\Models;

use Filament\Panel;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Profile;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Panel\Concerns\HasNotifications;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Modelo que representa a los usuarios del sistema.
 *
 * Implementa autenticación, verificación de email, roles, notificaciones y acceso al panel Filament.
 */


class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasPanelShield, HasNotifications;
    use HasRoles;

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * Atributos que deben ocultarse al serializar el modelo.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que deben castearse a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Establece el tipo de relación que tiene con otros modelos.
     */
    public function profiles(): HasOne
    {
        return $this->hasOne(Profile::class);
    }


    /**
     * Determina si el usuario puede acceder al panel de Filament.
     *
     * @param  \Filament\Panel  $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Envía la notificación para restablecimiento de contraseña.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
