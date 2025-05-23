<?php

namespace App\Models;

use App\Models\Concept;
use App\Models\Process;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'comment',
        'process_id',
        'concept_id',
        'profile_id',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'process_id' => 'integer',
        'concept_id' => 'integer',
        'profile_id' => 'integer',
    ];

    /**
     * Establishes the type of relationship it has with other models
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }
    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }


    // Función que actualiza el estado del proceso según los comentarios asociados
    public static function updateProcessState(Process $process): void
    {
        // Solo actualiza el estado si hay al menos 2 comentarios ----------
        // $commentCount = $process->comments()->count();
        // if ($commentCount < 2) {
        //     return;
        // }

        // Verifica si todos los comentarios tienen el concepto aprobado (concept_id = 1)
        $allApproved = $process->comments()->where('concept_id', 1)->count() === $process->comments()->count();

        // Verifica si al menos un comentario tiene el concepto "No aprobado" (concept_id = 2)
        $hasRejected = $process->comments()->where('concept_id', 2)->exists();

        // Si hay al menos un comentario rechazado, cambia el estado del proceso a 2 (Improbado)
        if ($hasRejected) {
            $process->update(['state' => 2]); // Estado "Improbado"
        }
        // Si todos los comentarios están aprobados y hay comentarios, cambia el estado a 1 (Aprobado)
        elseif ($allApproved && $process->comments()->count() > 0) {
            $process->update(['state' => 1]); // Estado "Aprobado"
        }
        // Si no hay comentarios, opcionalmente se puede dejar el proceso como "Pendiente"
        elseif ($process->comments()->count() === 0) {
            $process->update(['state' => 3]); // Estado "Pendiente"
        }
    }

    /**
     * Método boot del modelo para escuchar eventos de Eloquent
     */
    protected static function boot()
    {
        parent::boot();

        // Cuando se crea un comentario, actualiza el estado del proceso
        static::created(function (Comment $comment) {
            self::updateProcessState($comment->process);
        });

        // Cuando se actualiza un comentario, también actualiza el estado del proceso
        static::updated(function (Comment $comment) {
            self::updateProcessState($comment->process);
        });

        // Cuando se elimina un comentario, se vuelve a evaluar el estado del proceso
        static::deleted(function (Comment $comment) {
            self::updateProcessState($comment->process);
        });
    }


}
