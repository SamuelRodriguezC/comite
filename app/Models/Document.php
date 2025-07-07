<?php

namespace App\Models;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'type',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Establece el tipo de relación que tiene con otros modelos.
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }
}
