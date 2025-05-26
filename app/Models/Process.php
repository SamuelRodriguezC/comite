<?php

namespace App\Models;

use App\Models\Stage;
use App\Models\Comment;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Process extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'requirement',
        'state',
        'comment',
        'transaction_id',
        'completed',
        'delivery_date',
        'stage_id',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'state' => 'integer',
        'transaction_id' => 'integer',
        'completed' => 'boolean',
        'delivery_date' => 'datetime',
        'stage_id' => 'integer',
    ];

    /**
     * Establishes the type of relationship it has with other models
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }


    protected static function booted(): void
    {
        static::updating(function (Process $process) {
            if (
                $process->isDirty('state') &&
                $process->state == 1 &&
                $process->stage_id == 1
            ) {
                $process->completed = 1;
            }

            if (
                $process->isDirty('requirement') &&
                !empty($process->requirement)
            ) {
                $process->state = 6;
            }
        });

        // Al actualizar el proceso (después de guardar), validar si actualizar la transacción
        static::updated(function (Process $process) {
            $transaction = $process->transaction;

            // Solo si la transacción está en progreso
            if ($transaction && $transaction->status == \App\Enums\Status::ENPROGRESO->value) {
                // Verificar si todos los procesos están completados
                $allCompleted = $transaction->processes()->where('completed', 0)->count() === 0;

                if ($allCompleted) {
                    $transaction->status = \App\Enums\Status::COMPLETADO->value;
                    $transaction->save();
                }
            }
        });
    }



}
