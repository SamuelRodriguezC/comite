<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     * @var array
     */
    protected $fillable = [
        'acta',
        'comment',
        'resolution',
        'transaction_id',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'resolution' => 'integer',
        'transaction_id' => 'integer',
    ];

    /**
     * Establece el tipo de relación que tiene con otros modelos
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
