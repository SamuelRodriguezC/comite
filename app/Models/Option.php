<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo que representa una opción de grado disponible en el sistema.
 *
 * Cada opción contiene información sobre su nivel académico, componente (investigativo o no),
 * una descripción general y los requerimientos específicos. Las opciones están asociadas a múltiples transacciones.
 */
class Option extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'option',
        'level',
        'component',
        'description',
        'requeriment',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'level' => 'integer',
        'component' => 'integer',
    ];

    /**
     * Establece el tipo de relación que tiene con otros modelos.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
