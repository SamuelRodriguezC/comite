<?php

namespace App\Enums;

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
enum CertificateType: int
{
    case Student = 1;
    case Advisor = 2;
    case Evaluator = 3;
}
