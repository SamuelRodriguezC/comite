<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum State: int implements HasLabel, HasColor
{
    case APROBADO = 1;
    case IMPROBADO = 2;
    case PENDIENTE = 3;
    case APLAZADO = 4;
    case CANCELADO = 5;
    case ENTREGADO = 6;
    case VENCIDO = 7;
    /**
     * Generates function to display a label
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::APROBADO => 'Aprobado',
            self::IMPROBADO => 'No aprobado',
            self::PENDIENTE => 'Pendiente',
            self::APLAZADO => 'Aplazado',
            self::CANCELADO => 'Cancelado',
            self::ENTREGADO => 'Entregado',
            self::VENCIDO => 'Vencido',
        };
    }
    /**
     * Generates function to obtain color according to the case
     */
    public function getColor(): string|array|null
    {
        return match ($this){
            self::APROBADO => 'success',
            self::IMPROBADO => 'danger',
            self::PENDIENTE => 'warning',
            self::APLAZADO => 'gray',
            self::CANCELADO => 'danger',
            self::ENTREGADO => 'info',
            self::VENCIDO => 'danger',
        };
    }
}
