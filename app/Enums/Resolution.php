<?php
/**
 * Enum Resolution
 *
 * Representa el resultado de una resolución académica.
 * Actualmente no está en uso dentro del sistema.
 *
 * @deprecated Este enum no está siendo utilizado en el sistema actual.
 *
 * @package App\Enums
 */
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum Resolution: int implements HasLabel, HasColor
{
    case ACEPTADO = 1;
    case RECHAZADO = 2;
    case PENDIENTE = 3;
    case APLAZADO = 4;

    /**
     * Devuelve una etiqueta legible para mostrar el texto del componente en la interfaz de usuario.
     *
     * @return string|null
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
     * Devuelve el color asociado al la resolución, usado en etiquetas o badges.
     *
     * @return string
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
