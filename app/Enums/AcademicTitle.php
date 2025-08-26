<?php

namespace App\Enums;


/**
 * Enum Academic Title
 *
 * Representa los títulos académicos que un usuario puede tener.
 *
 *
 *
 * @package App\Enums
 */
enum AcademicTitle: int
{
    case TECNICO = 1;
    case TECNOLOGO = 2;
    case PROFESIONAL = 3;
    case LICENCIADO = 4;
    case ESPECIALISTA = 5;
    case MAGISTER = 6;
    case DOCTOR = 7;

    /**
     * Devuelve una etiqueta legible para mostrar en la interfaz.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::TECNICO => 'Técnico(a)',
            self::TECNOLOGO => 'Técnólogo(a)',
            self::PROFESIONAL => 'Profesional',
            self::LICENCIADO => 'Licenciado(a)',
            self::ESPECIALISTA => 'Especialista',
            self::MAGISTER => 'Master',
            self::DOCTOR => 'Doctor(a)',
        };
    }
}
