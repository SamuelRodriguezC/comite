<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Course;
use App\Models\Option;
use App\Models\Process;
use App\Models\Profile;
use App\Models\Certificate;
use App\Models\ProfileTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'component' => 'integer',
        // 'enabled' => \App\Enums\Enabled::class,
        'option_id' => 'integer',
    ];

    // ----------------------- RELACIONES -----------------------
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
    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
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

    // ---------- VERIFICAR SI LA TRANSACCIÓN ES EDITABLE (ANTES DE 12 HRS) ----------
    public function isEditable(): bool
    {
        return $this->created_at->diffInHours(now()) < 12;
    }

    // ---------- VALIDAR - EL ESTUDIANTE NO PUEDE CREAR TRANSACCIONES SI TIENE AL MENOS UNA HABILITADA  ----------
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
}
