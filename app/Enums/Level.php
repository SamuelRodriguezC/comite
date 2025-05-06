<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum Level: int implements HasLabel
{
    case PREGRADO = 1;
    case POSGRADO = 2;
    case NOAPLICA = 3;
    /**
     * Generates function to display a label
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
