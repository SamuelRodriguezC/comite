<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\Role;
use App\Enums\Status;
use App\Models\Course;
use App\Models\Option;
use App\Models\Process;
use App\Models\Profile;
use App\Models\Certificate;
use App\Models\ProfileTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\TransactionNotifications;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * Modelo que representa una transacción de opción de grado.
 *
 * Cada transacción puede estar asociada a múltiples perfiles (estudiantes, asesores, evaluadores),
 * procesos, cursos y una opción de grado específica.
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'component',
        'option_id',
        'status',
        'enabled',
        'created_at'
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'component' => 'integer',
        // 'enabled' => \App\Enums\Enabled::class,
        'status' => 'integer',
        'option_id' => 'integer',
    ];

    /**
     * Establece el tipo de relación que tiene con otros modelos.
     */
    public function profileTransactions()
    {
        return $this->hasMany(ProfileTransaction::class);
    }

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class)
            ->withPivot('courses_id', 'role_id');
    }
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function studentsCertificate(): HasOne
    {
        return $this->hasOne(Certificate::class)->where('type', 1); // 1 = estudiante
    }

    public function advisorCertificates(): HasMany
    {
        return $this->hasMany(Certificate::class)->where('type', 2); // 2 = asesor
    }
    public function finalEvaluationCertificates(): HasMany
    {
        return $this->hasMany(Certificate::class)->where('type', 3); // 3 = Evaluador
    }

    public function processes(): HasMany
    {
        return $this->hasMany(Process::class);
    }
        public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'profile_transaction', 'transaction_id', 'courses_id')
                    ->using(ProfileTransaction::class)
                    ->withPivot('profile_id', 'role_id');
    }


    // ----------------------- MÉTODOS ------------------------

    /**
     * Obtiene los nombres de los cursos únicos asociados a los perfiles de la transacción.
     *
     * @return string
     */
    public function getCoursesAttribute()
    {
        return $this->profiles
            ->map(function ($profile) {
                // Prueba si llega el curso
                $course = \App\Models\Course::find($profile->pivot->courses_id);
                return $course?->course ?? 'Curso no encontrado';
            })
            ->filter()
            ->unique()
            ->implode(', ');
    }

    /**
     * Determina si la transacción puede ser editada (menos de 12 horas desde su creación).
     */
    public function isEditable(): bool
    {
        return $this->created_at->diffInHours(now()) < 12;
    }


    /**
     * Verifica si el estudiante actual puede crear una nueva transacción.
     * Solo es posible si no tiene ninguna transacción habilitada.
     */
    public static function canCreate(): bool
    {
        $user = Auth::user();

        if (!$user || !$user->profiles) {
            return false;
        }

        $profileId = $user->profiles->id;

        // Verifica si existe al menos una transacción habilitada para este perfil
        $hasEnabledTransaction = Transaction::whereHas('profiles', function (Builder $query) use ($profileId) {
            $query->where('profile_id', $profileId)
                ->where('role_id', 1); // Solo para rol estudiante
        })
        ->where('enabled', 1) // Habilitado
        ->exists();

        return !$hasEnabledTransaction;
    }


    /**
     * Verifica si el usuario autenticadot tiene acceso a la transacción.
     * Los coordinadores y super administradores siempre tienen acceso.
     */
    public function userHasAccess(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Roles privilegiados
        if ($user->hasRole('Coordinador') || $user->hasRole('Super administrador')) {
            return true;
        }

        // Verifica si el usuario pertenece a la transacción
        return $this->profiles()->where('user_id', $user->id)->exists();
    }

    public function getFinalEvaluationCertificateForProfile(?int $profileId = null): ?Certificate
    {
        $profileId ??= Auth::user()?->profiles?->id;

        if (! $profileId) {
            return null;
        }

        return $this->finalEvaluationCertificates()
            ->where('profile_id', $profileId)
            ->first();
    }

    public function hasFinalEvaluationCertificateForProfile(?int $profileId = null): bool
    {
        return (bool) $this->getFinalEvaluationCertificateForProfile($profileId);
    }

    // ----------------------- EVENTOS ------------------------

    /**
     * Evento para enviar notificación cuando cambia el estado de la transacción.
     */
    protected static function booted()
{
    static::creating(function (Transaction $transaction) {
        if (!$transaction->component) {
            $transaction->component = 0;
        }
        if (!$transaction->option_id) {
            $transaction->option_id = 1; // asegúrate que exista
        }
        if (!$transaction->status) {
            $transaction->status = 0;
        }
        if (!$transaction->enabled) {
            $transaction->enabled = 1;
        }
    });

    static::updated(function (Transaction $transaction) {
        if ($transaction->isDirty('status')) {
            TransactionNotifications::sendStatusChanged($transaction);
        }
    });
}

}
