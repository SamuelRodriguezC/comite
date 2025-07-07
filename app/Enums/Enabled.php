<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;


/**
 * Enum Enabled
 *
 * Indica si una transacción está habilitada o deshabilitada.
 * Se utiliza para controlar el acceso de los usuarios a las funcionalidades de la transacción.
 * Implementa HasLabel para su integración visual con Filament.
 *
 * @package App\Enums
 */

enum Enabled: int implements HasLabel
{
    case HABILITADO = 1; // Todos los usuarios vinculados pueden realizar sus funciones.
    case DESHABILITADO = 2; // Solo el coordinador puede modificar; los demás solo tienen acceso de lectura.


    /**
     * Devuelve una etiqueta legible (Sí/No) para mostrar en la interfaz.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::HABILITADO => 'Si',
            self::DESHABILITADO => 'No',
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
            self::HABILITADO => 'heroicon-o-check-circle',
            self::DESHABILITADO => 'heroicon-o-x-circle',
        };
    }

    /**
     * Devuelve el color asociado al estado, usado en etiquetas o badges.
     *
     * @return string
     */
    public function getColor(): string
    {
        return match ($this) {
            self::HABILITADO => 'success',
            self::DESHABILITADO => 'danger',
        };
    }
}

