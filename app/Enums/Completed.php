<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Enum Completed
 *
 * Indica si un proceso dentro de una transacción ha sido completado o no.
 *
 * El estado por defecto es 'No'. Este enum se utiliza para representar visualmente
 * en la interfaz si un proceso ha finalizado, mediante etiquetas, íconos y colores compatibles con Filament.
 *
 * @package App\Enums
 */
enum Completed: int implements HasLabel
{
    case SI = 1;
    case NO = 0;


    /**
     * Devuelve una etiqueta legible para mostrar en la interfaz.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::SI => 'Si',
            self::NO => 'No',
        };
    }

    /**
     * Devuelve el nombre del ícono Heroicon asociado al estado.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::SI => 'heroicon-o-check-circle',
            self::NO => 'heroicon-o-x-circle',
        };
    }

    /**
     * Devuelve el color asociado al estado, usado en componentes visuales de Filament.
     *
     * @return string
     */
    public function getColor(): string
    {
        return match ($this) {
            self::SI => 'success',
            self::NO => 'danger',
        };
    }
}
