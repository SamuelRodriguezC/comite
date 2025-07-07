<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Enum Level
 *
 * Representa el nivel académico de un usuario dentro del sistema.
 * Aplica principalmente para roles de tipo estudiante (Pregrado / Posgrado), y otros roles (No Aplica).
 * Implementa HasLabel para su integración con los componentes de Filament.
 *
 * @package App\Enums
 */
enum Level: int implements HasLabel
{
    // Para Estudiantes
    case PREGRADO = 1;
    case POSGRADO = 2;

    // Para Asesores, Evaluadores y Coordinadores
    case NOAPLICA = 3;

    /**
     * Devuelve una etiqueta legible para el nivel académico, usada en la interfaz de usuario.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {

            self::PREGRADO => 'Pregrado',
            self::POSGRADO => 'Posgrado',
            self::NOAPLICA => 'No Aplica',
        };
    }
}
