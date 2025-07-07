<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum Certification
 *
 * Representa el estado de certificación de una opción de grado.
 * No está siendo utilizado actualmente en el sistema.
 *
 * @deprecated Este enum no está siendo utilizado en el sistema actual.
 *
 * @package App\Enums
 */
enum Certification: int implements HasLabel, HasColor
{
    case NOCERTIFICADO = 1;    // El estudiante aún no ha sido certificado.
    case PORCERTIFICAR = 2;    // La certificación está en trámite.
    case CERTIFICADO = 3;      // El estudiante ha sido certificado.

    /**
     * Devuelve una etiqueta legible para el estado de certificación.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::NOCERTIFICADO => 'No certificado',
            self::PORCERTIFICAR => 'Por certificar',
            self::CERTIFICADO => 'Certificado',
        };
    }

    /**
     * Devuelve el color asociado al estado de certificación.
     * Usado en etiquetas, badges o íconos dentro de Filament.
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NOCERTIFICADO => 'danger',
            self::PORCERTIFICAR => 'warning',
            self::CERTIFICADO => 'success',
        };
    }
}
