<?php

namespace App\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum Status
 *
 * Representa los distintos estados posibles de una Transacción (opción de grado) dentro del sistema.
 * Implementa las interfaces HasLabel y HasColor para integrarse con componentes de Filament.
 *
 * Flujo normal:
 * En Progreso → Completado → Por Certificar → Certificado.
 *
 * @package App\Enums
 */
enum Status: int implements HasLabel, HasColor
{
    case ENPROGRESO = 1; // Estado inicial de la transacción.
    case COMPLETADO = 2; // Todos los procesos han finalizado.
    case PORCERTIFICAR = 3; // Evaluador envía solicitud de certificación a coordinador.
    case CERTIFICADO = 4; // El coordinador generó acta de terminación.
    case CANCELADO = 5; //El proceso ha sido cancelado manualmente por el coordinador.

    /**
     * Retorna una etiqueta legible para el estado, usada en la interfaz de usuario.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::ENPROGRESO => 'En Progreso',
            self::COMPLETADO => 'Completado',
            self::PORCERTIFICAR => 'Por Certificar',
            self::CERTIFICADO => 'Certificado',
            self::CANCELADO => 'Cancelado',
        };
    }

    /**
     * Devuelve el color asociado al estado, usado en los badges o etiquetas de Filament.
     *
     * Colores disponibles: info, teal, warning, success, danger.
     *
     * @return string|array|null
     */
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

    /**
     * Proporciona una breve descripción del estado, para tooltips en la interfaz.
     *
     * @return string|null
     */
    public function getTooltip(): ?string
    {
        return match ($this) {
            self::ENPROGRESO => 'La Opción tiene procesos iniciados pero no han sido finalizados',
            self::COMPLETADO => 'Todos los procesos de la Opción han sido finalizados',
            self::PORCERTIFICAR => 'Se está gestionando la certificación',
            self::CERTIFICADO => 'El estudiante ha sido certificado',
            self::CANCELADO => 'La opción ha sido cancelada',
        };
    }



    /**
     * Determina si un estado específico bloquea la edición de campos.
     *
     * Se utiliza, por ejemplo, para deshabilitar ciertos componentes del formulario
     * cuando la Transacción está en alguno de los estados finales (Certificado,
     * Por Certificar o Cancelado).
     *
     * @param int $status Estado actual de la Transacción.
     * @return bool True si el estado bloquea la edición, False en caso contrario.
     */
    public static function isLocked(int $status): bool
    {
        return in_array($status, [
            self::CERTIFICADO->value,
            self::PORCERTIFICAR->value,
            self::CANCELADO->value,
        ], true);
    }

   /**
     * Proporciona un mensaje de ayuda o advertencia según el estado actual.
     *
     * Estos mensajes se muestran en la interfaz (ej. como helperText o tooltip)
     * para informar al usuario por qué no puede realizar ciertas acciones
     * dependiendo del estado de la Transacción.
     *
     * @param int|null $status Estado actual de la Transacción.
     * @return string|null Mensaje explicativo asociado al estado, o null si no aplica.
     */
    public static function helperMessage(?int $status): ?string
    {
        return match ($status) {
            self::CERTIFICADO->value   => 'El estudiante ya fue Certificado, no puedes editar este campo',
            self::PORCERTIFICAR->value => 'Solicitud de certificación enviada exitosamente',
            self::CANCELADO->value     => 'La Opción está cancelada; no puedes enviar solicitud de certificación',
            default                    => null,
        };
    }

}


