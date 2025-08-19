<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum State
 *
 * Representa los posibles estados de un proceso académico dentro del sistema.
 * Implementa las interfaces HasLabel y HasColor para su integración con Filament.
 *
 * Flujo normal:
 * Pendiente → Entregado → Aprobado / Improbado
 *
 * @package App\Enums
 */
enum State: int implements HasLabel, HasColor
{
    case APROBADO = 1; //Todos los evaluadores aprobaron el proceso.
    case IMPROBADO = 2; //Al menos un evaluador reprobó el proceso.
    case PENDIENTE = 3; // Estado inicial del proceso, sin entrega.
    case APLAZADO = 4; // Proceso aplazado manualmente por el coordinador.
    case CANCELADO = 5; // Proceso cancelado manualmente por el coordinador.
    case ENTREGADO = 6; // El estudiante subió el documento requerido.
    case VENCIDO = 7; // El proceso superó su fecha límite sin acción.


    /**
     * Retorna una etiqueta legible para el estado, usada en la interfaz de usuario.
     *
     * @return string|null
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
     * Devuelve el color asociado al estado, usado por los componentes de Filament.
     *
     * Colores disponibles: success, danger, warning, info, gray.
     *
     * @return string|array|null
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


    public function getIcon(): string|array|null
    {
        return match ($this){
            self::APROBADO => 'heroicon-o-check-badge',
            self::IMPROBADO => 'heroicon-o-archive-box-x-mark',
            self::PENDIENTE => 'heroicon-o-ellipsis-horizontal-circle',
            self::APLAZADO => 'heroicon-o-calendar-days',
            self::CANCELADO => 'heroicon-o-x-circle',
            self::ENTREGADO => 'heroicon-o-archive-box',
            self::VENCIDO => 'heroicon-o-clock',
        };
    }
}
