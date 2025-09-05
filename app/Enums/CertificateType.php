<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
/**
 * Enum CertificateType
 *
 * Representa el tipo de certificado generado al finalizar una transacción.
 * Este valor se almacena en el campo `type` del modelo Certificate.
 *
 * Los tipos de certificado pueden ser:
 *
 * - Student (1): Certificado generado para el(los) estudiante(s) que indica que se culminó el proceso de opción de grado.
 * - Advisor (2): Certificado generado para el asesor que acompañó el proceso de opción de grado.
 *
 * @package App\Enums
 */
enum CertificateType: int implements HasLabel
{
    case TERMINACION = 1;
    case CERTIFICADO_ASESOR = 2;
    case EVALUACION_FINAL = 3;
    case EVALUACION_ANTEPROYECTO = 4;

    /**
     * Devuelve una etiqueta legible para mostrar en la interfaz.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::TERMINACION => 'Terminación',
            self::CERTIFICADO_ASESOR => 'Director',
            self::EVALUACION_FINAL => 'Evaluación Final',
            self::EVALUACION_ANTEPROYECTO => 'Evaluación Ante-Proyecto',
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
            self::TERMINACION => 'success',
            self::CERTIFICADO_ASESOR => 'info',
            self::EVALUACION_FINAL => 'warning',
            self::EVALUACION_ANTEPROYECTO => 'teal',
        };
    }
}
