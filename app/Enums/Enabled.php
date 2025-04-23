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
            self::HABILITADO => 'Si',
            self::DESHABILITADO => 'No',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::HABILITADO => 'heroicon-o-check-circle',
            self::DESHABILITADO => 'heroicon-o-x-circle',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::HABILITADO => 'success',
            self::DESHABILITADO => 'danger',
        };
    }
}

