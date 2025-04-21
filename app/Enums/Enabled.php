<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum Enabled: int implements HasLabel
{
    case HABILITADO = 1;
    case DESHABILITADO = 2;
    /**
     * Generates function to display a label
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::HABILITADO => 'Habilitado',
            self::DESHABILITADO => 'Deshabilitado',
        };
    }
}

