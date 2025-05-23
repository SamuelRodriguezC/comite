<?php

namespace App\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;


enum Status: int implements HasLabel, HasColor
{
    case PENDIENTE = 1;
    case ENPROGRESO = 2;
    case COMPLETADO = 3;
    case FINALIZADO = 4;
    case CANCELADO = 5;
    case INCOMPLETO = 6;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente',
            self::ENPROGRESO => 'En Progreso',
            self::COMPLETADO => 'Completado',
            self::FINALIZADO => 'Finalizado',
            self::CANCELADO => 'Cancelado',
            self::INCOMPLETO => 'Incompleto',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDIENTE => 'warning',
            self::ENPROGRESO => 'info',
            self::COMPLETADO => 'success',
            self::FINALIZADO => 'success',
            self::CANCELADO => 'danger',
            self::INCOMPLETO => 'danger',
        };
    }

    public function getTooltip(): ?string
    {
        return match ($this) {
            self::PENDIENTE => 'El ticket ha sido creado pero no tiene procesos iniciados',
            self::ENPROGRESO => 'El ticket tiene procesos iniciados pero no han sido finalizados',
            self::COMPLETADO => 'Todos los procesos del ticket han sido finalizados y aprobados',
            self::FINALIZADO => 'Se ha generado un certificado',
            self::CANCELADO => 'El coordinador ha cancelado el ticket',
            self::INCOMPLETO => 'Todos los procesos han sido finalizados pero al menos uno ha sido improbado',
        };
    }

}

