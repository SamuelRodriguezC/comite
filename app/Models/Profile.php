<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Role;
use App\Models\Document;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo que representa el perfil de un usuario dentro del sistema.
 *
 * Un perfil contiene información personal y académica del usuario, y se asocia con múltiples
 * transacciones, documentos, roles y cursos.
 */
class Profile extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'document_number',
        'phone_number',
        'level',
        'document_id',
        'user_id',
        'comment_id'
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'document_number' => 'integer',
        'phone_number' => 'integer',
        'level' => 'integer',
        'document_id' => 'integer',
        'user_id' => 'integer',
        'comment_id' => 'integer',
    ];

    // -------------------- RELACIONES CON OTROS MODELOS  --------------------
    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class)->withPivot('courses_id', 'role_id');
    }
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }
    public function signature()
    {
        return $this->hasOne(Signature::class);
    }




    // -------------------- MÉTODOS  --------------------

    /**
     * Obtiene el nombre del curso asociado al perfil en su primera transacción.
     *
     * @return string|null Nombre del curso o null si no tiene curso asignado.
     */
    public function getCursoAttribute()
    {
        // Asumiendo que una transacción tiene un solo perfil en esta relación
        $profile = $this->profiles()->first();
        if (!$profile || !$profile->pivot || !$profile->pivot->courses_id) {
            return null;
        }
        return \App\Models\Course::find($profile->pivot->courses_id)?->course;
    }

    /**
     * Devuelve el nombre completo del perfil concatenando nombre y apellido.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

    /**
     * Obtiene el curso de un perfil específico dentro de una transacción dada.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \App\Models\Course|null  El curso asociado en la transacción o null si no tiene.
    */
    public function courseInTransaction(Transaction $transaction)
    {
        $pivot = $this->transactions()->where('transaction_id', $transaction->id)->first()?->pivot;
        return $pivot ? Course::find($pivot->courses_id) : null;
    }


    /**
     * Verifica si el perfil ya tiene un certificado asociado a una transacción.
    *
    * @param  \App\Models\Transaction  $transaction
    * @param  int  $type  Tipo de certificado (por defecto 2 = asesor).
    * @return bool
    */
    public function hasCertificate(Transaction $transaction, int $type = 2): bool
    {
        // Busca certificado asociado a esta transacción y tipo, donde el perfil coincide con la transacción
        return Certificate::where('transaction_id', $transaction->id)
            ->where('type', $type)
            ->where('profile_id', $this->id)
            ->exists();
    }



}
