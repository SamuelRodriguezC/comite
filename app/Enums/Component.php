<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Enum Component
 *
 * Representa los componentes académicos que puede tener una opción de grado.
 * Puede ser de tipo investigativo o no investigativo.
 * Implementa la interfaz HasLabel para integrarse con la interfaz de usuario de Filament.
 *
 * @package App\Enums
 */
enum Component: int implements HasLabel
{
    case INVESTIGATIVO = 1;
    case NO_INVESTIGATIVO = 2;


    /**
     * Devuelve una etiqueta legible para mostrar en la interfaz de usuario.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::INVESTIGATIVO => 'Investigativo',
            self::NO_INVESTIGATIVO => 'No investigativo',
        };
    }
}

