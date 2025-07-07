<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo que representa un rol dentro del sistema.
 *
 * Este modelo es usado para la gestión de roles y permisos,
 * en conjunto con el paquete Spatie Laravel-Permission.
 */
class Role extends Model
{
    //
    use HasFactory;

     /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];
}
