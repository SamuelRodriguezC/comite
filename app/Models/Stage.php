<?php

namespace App\Models;

use App\Models\Process;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo que representa una etapa dentro del flujo de un proceso académico.
 *
 * Las etapas agrupan procesos que deben cumplirse dentro de una transacción.
 */
class Stage extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'stage',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Establece el tipo de relación que tiene con otros modelos.
     */
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class);
    }
}
