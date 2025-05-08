<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Role;
use App\Models\Document;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'document_number',
        'phone_number',
        'level',
        'document_id',
        'user_id',
        'comment_id'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'document_number' => 'integer',
        'phone_number' => 'integer',
        'level' => 'integer',
        'document_id' => 'integer',
        'user_id' => 'integer',
        'comment_id' => 'integer',
    ];

    /**
     * Establishes the type of relationship it has with other models
     */
    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class)->withPivot('courses_id', 'role_id');
    }
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getCursoAttribute()
    {
        // Asumiendo que una transacción tiene un solo perfil en esta relación
        $profile = $this->profiles()->first();
        if (!$profile || !$profile->pivot || !$profile->pivot->courses_id) {
            return null;
        }
        return \App\Models\Course::find($profile->pivot->courses_id)?->course;
    }

    // Crea función de nombre completo
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

}
