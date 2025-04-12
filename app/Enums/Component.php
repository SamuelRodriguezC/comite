<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum Component: int implements HasLabel
{
    case INVESTIGATIVO = '1';
    case NO_INVESTIGATIVO = '2';
    /**
     * Generates function to display a label
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::INVESTIGATIVO => 'Investigativo',
            self::NO_INVESTIGATIVO => 'No investigativo',
        };
    }
}

