<?php

namespace App\Enums;
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
enum Seccional: int implements HasLabel
{
    case BOGOTA = 1;
    case BARRANQUILLA = 2;
    case CALI = 3;
    case CARTAGENA = 4;
    case CUCUTA = 5;
    case PEREIRA = 6;
    case ELSOCORRO = 7;


    /**
     * Retorna una etiqueta legible para la seccional, usada en la interfaz de usuario.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::BOGOTA => 'Bogotá D.C.',
            self::BARRANQUILLA => 'Barranquilla',
            self::CALI => 'Cali',
            self::CARTAGENA => 'Cartagena',
            self::CUCUTA => 'Cúcuta',
            self::PEREIRA => 'Pereira',
            self::ELSOCORRO => 'Socorro',
        };
    }

}


