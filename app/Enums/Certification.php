<?php
namespace App\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Establish cases
 */
enum Certification: int implements HasLabel, HasColor
{
    case NOCERTIFICADO = 1;
    case PORCERTIFICAR = 2;
    case CERTIFICADO = 3;
    /**
     * Generates function to display a label
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
     * Generates function to obtain color according to the case
     */
    public function getColor(): string|array|null
    {
        return match ($this){
            self::NOCERTIFICADO => 'danger',
            self::PORCERTIFICAR => 'warning',
            self::CERTIFICADO => 'success',
        };
    }
}
