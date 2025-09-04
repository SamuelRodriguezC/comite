<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\CertificateType;

/**
 * Modelo Certificate
 *
 * Representa el certificado generado al finalizar una transacción (proceso de opción de grado).
 * Contiene información como el número de acta, comentarios y resolución asociada.
 */
class Certificate extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'acta',
        'transaction_id',
        'signer_id',
        'type',
        'profile_id',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'resolution' => 'integer',
        'transaction_id' => 'integer',
        'signer_id' => 'integer',
        'profile_id' => 'integer',
        'type' => CertificateType::class,
    ];

    // -------------------- RELACIONES CON OTROS MODELOS  --------------------
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(Signer::class);
    }
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
    protected static function booted()
{
    static::creating(function (Certificate $certificate) {
        if (!$certificate->acta) {
            $certificate->acta = 'placeholder.pdf';
        }

        if (!$certificate->transaction_id) {
            $certificate->transaction_id = 1; // asegúrate que exista
        }

        if (!$certificate->type) {
            $certificate->type = 1; // estudiante por defecto
        }

        if (!$certificate->signer_id) {
            $certificate->signer_id = 1; // asigna un ID válido de firmante
        }
    });
}
}
