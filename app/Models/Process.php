<?php

namespace App\Models;

use App\Models\Stage;
use App\Models\Comment;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\TransactionNotifications;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo que representa un proceso académico dentro de una transacción.
 *
 * Cada proceso está asociado a una etapa, una transacción y puede tener múltiples comentarios.
 * Su estado y completitud afectan directamente el flujo y finalización de la transacción.
 */
class Process extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
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
     * Los atributos que deben convertirse a tipos nativos.
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
     * Establece el tipo de relación que tiene con otros modelos.
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


    /**
     * Eventos del ciclo de vida del modelo.
     *
     * - Al actualizar: se marcan automáticamente procesos como completados
     *   si cumplen ciertas condiciones.
     * - Al actualizar: si cambia el estado, se notifica al estudiante.
     * - Si todos los procesos están completados, se marca la transacción como completada.
     */
    protected static function booted(): void
    {
        static::updating(function (Process $process) {
            // Lógica existente
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

            // --- NUEVA LÓGICA DE VENCIMIENTO BIDIRECCIONAL ---
            if ($process->delivery_date) {
                if ($process->delivery_date->isPast() && $process->state != 7) {
                    // La fecha ya pasó, marcar como vencido
                    $process->state = 7; // 7 = Vencido
                } elseif ($process->delivery_date->isFuture() && $process->state == 7) {
                    // La fecha se movió a futuro y estaba vencido → pasar a Aplazado
                    $process->state = 4; // 4 = Aplazado
                }
            }
        });


        // Lógica de actualización del proceso
        static::updated(function (Process $process) {
            $transaction = $process->transaction;

            if ($process->isDirty('state') && $transaction) {
                $studentProfiles = $transaction->profileTransactions()
                    ->where('role_id', 1)
                    ->with('profile.user')
                    ->get();

                foreach ($studentProfiles as $profileTransaction) {
                    $user = $profileTransaction->profile->user;

                    if ($user) {
                        \App\Notifications\ProcessNotification::stateUpdated($transaction, $user, $process);
                    }
                }
            }

            if ($transaction && $transaction->status == \App\Enums\Status::ENPROGRESO->value) {
                $allCompleted = $transaction->processes()->where('completed', 0)->count() === 0;

                if ($allCompleted) {
                    $transaction->status = \App\Enums\Status::COMPLETADO->value;
                    TransactionNotifications::sendStatusChanged($transaction);
                    $transaction->save();
                }
            }
        });
    }
}
