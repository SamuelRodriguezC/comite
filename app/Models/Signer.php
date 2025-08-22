<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Signer extends Model
{
    /** @use HasFactory<\Database\Factories\SignerFactory> */
    use HasFactory;

     protected $fillable = [
        'first_name',
        'last_name',
        'faculty',
        'seccional',
        'signature',
    ];

     /**
     * Un firmador puede tener muchos certificados
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Nombre completo + Facultad (para selects y vistas)
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}  - Facultad de {$this->faculty}";
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Acceso a la URL de la firma
    public function getSignatureUrlAttribute(): ?string
    {
        if (! $this->signature) {
            return null;
        }

        return route('signatures.show', basename($this->signature));
    }
}
