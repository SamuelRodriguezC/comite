<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo que representa un curso académico.
 *
 * Cada curso está asociado a un nivel y puede estar vinculado
 * a múltiples perfiles a través de la tabla pivot `profile_transaction`.
 */
class Course extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'course',
        'level',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'level' => 'integer',
    ];

    /**
     * Establece el tipo de relación que tiene con otros modelos.
     */
    public function profileTransactions(): HasMany
    {
        return $this->hasMany(ProfileTransaction::class, 'courses_id');
    }
}
