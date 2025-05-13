<?php

namespace App\Models;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Role;
use App\Models\Option;
use App\Models\Process;
use App\Models\Profile;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'component',
        'option_id',
        'certification',
        'enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'component' => 'integer',
        'option_id' => 'integer',
    ];

    /**
     * Establishes the type of relationship it has with other models
     */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class)
            ->withPivot('courses_id', 'role_id');
    }
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class);
    }

    public function getCoursesAttribute()
    {
        return $this->profiles
            ->map(function ($profile) {
                // Prueba si llega el curso
                $course = \App\Models\Course::find($profile->pivot->courses_id);
                return $course?->course ?? 'Curso no encontrado';
            })
            ->filter()
            ->unique()
            ->implode(', ');
    }

    // ---------- VERIFICAR SI LA TRANSACCIÓN ES EDITABLE (ANTES DE 12 HRS) ----------
    public function isEditable(): bool
    {
        return $this->created_at->diffInHours(now()) < 12;
    }
}
