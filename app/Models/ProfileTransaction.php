<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo que representa la relación entre un perfil y una transacción.
 *
 * Esta tabla pivote personalizada permite almacenar información adicional como:
 * - Curso asociado al perfil dentro de la transacción.
 * - Rol desempeñado por el perfil (estudiante, asesor, evaluador, etc.).
 */
class ProfileTransaction extends Model
{
    /**
     * Nombre explícito de la tabla pivote.
     *
     * @var string
     */
    protected $table = 'profile_transaction';

    /**
     * Definir si la tabla tiene (created - updated).
     *
     * @var string
     */
    public $timestamps = false;


    /**
     * Atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'transaction_id',
        'courses_id',
        'role_id'
    ];

    /**
     * Establece el tipo de relación que tiene con otros modelos.
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function courses(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // retorna la colección de roles que tiene el usuario dueño del perfil vinculado a esta transacción
    public function availableRoles()
    {
        return $this->profile->user->roles;
    }

}
