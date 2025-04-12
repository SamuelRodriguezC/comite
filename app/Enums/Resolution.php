<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum Resolution: int implements HasLabel, HasColor
{
    case ACEPTADO = '1';
    case RECHAZADO = '2';
    case PENDIENTE = '3';
    case APLAZADO = '4';
    /**
     * Generates function to display a label
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACEPTADO => 'Aceptado',
            self::RECHAZADO => 'Rechazado',
            self::PENDIENTE => 'Pendiente',
            self::APLAZADO => 'Aplazado',
        };
    }
    /**
     * Generates function to obtain color according to the case
     */
    public function getColor(): string|array|null
    {
        return match ($this){
            self::ACEPTADO => 'success',
            self::RECHAZADO => 'danger',
            self::PENDIENTE => 'warning',
            self::APLAZADO => 'gray',
        };
    }
}
