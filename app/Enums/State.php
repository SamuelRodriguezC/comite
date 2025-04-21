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
    /**
     * Generates function to display a label
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::APROBADO => 'Aprobado',
            self::IMPROBADO => 'Improbado',
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
        };
    }
}
