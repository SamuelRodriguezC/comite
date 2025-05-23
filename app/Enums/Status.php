<?php

namespace App\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;


enum Status: int implements HasLabel, HasColor
{
    // case PENDIENTE = 1;
    case ENPROGRESO = 1;
    case COMPLETADO = 2;
    case PORCERTIFICAR = 3;
    case CERTIFICADO = 4;
    case CANCELADO = 5;

    public function getLabel(): ?string
    {
        return match ($this) {
            // self::PENDIENTE => 'Pendiente',
            self::ENPROGRESO => 'En Progreso',
            self::COMPLETADO => 'Completado',
            self::PORCERTIFICAR => 'Por Certificar',
            self::CERTIFICADO => 'Certificado',
            self::CANCELADO => 'Cancelado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            // self::PENDIENTE => 'warning',
            self::ENPROGRESO => 'info',
            self::COMPLETADO => 'teal',
            self::PORCERTIFICAR => 'warning',
            self::CERTIFICADO => 'success',
            self::CANCELADO => 'danger',

        };
    }

    public function getTooltip(): ?string
    {
        return match ($this) {
            // self::PENDIENTE => 'La Opción ha sido creado pero no tiene procesos iniciados',
            self::ENPROGRESO => 'La Opción tiene procesos iniciados pero no han sido finalizados',
            self::COMPLETADO => 'Todos los procesos de la Opción han sido finalizados',
            self::PORCERTIFICAR => 'Se está gestionando la certificación',
            self::CERTIFICADO => 'El estudiante ha sido certificado',
            self::CANCELADO => 'El se ha cancelado la Opción',
        };
    }

}


