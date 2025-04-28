<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum Completed: int implements HasLabel
{
    case SI = 1;
    case NO = 0;
    /**
     * Generates function to display a label
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::SI => 'Si',
            self::NO => 'No',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::SI => 'heroicon-o-check-circle',
            self::NO => 'heroicon-o-x-circle',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SI => 'success',
            self::NO => 'danger',
        };
    }
}

