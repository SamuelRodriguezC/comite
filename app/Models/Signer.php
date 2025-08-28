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
     * Casts automáticos para atributos del modelo.
     *
     * Se convierte el campo "seccional" en una Enum \App\Enums\Seccional.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'seccional' => \App\Enums\Seccional::class,
    ];



    // -------------------- RELACIONES CON OTROS MODELOS  --------------------
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }


    // -------------------- MÉTODOS  --------------------


    /**
     * Atributo accesor para mostrar un nombre completo
     * acompañado de la facultad a la que pertenece.
     *
     * Ideal para dropdowns, selects o vistas.
     *
     * Ejemplo: "Carlos Pérez - Facultad de Ingeniería"
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}  - Facultad de {$this->faculty}";
    }

    /**
     * Atributo accesor para mostrar el nombre completo
     * sin información adicional.
     *
     * Ejemplo: "Carlos Pérez"
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Atributo accesor que devuelve la URL pública
     * de la firma del firmador, si existe.
     *
     * @return string|null
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if (! $this->signature) {
            return null;
        }
        // Genera una ruta hacia la firma, usando su nombre de archivo
        return route('signatures.show', basename($this->signature));
    }
}
