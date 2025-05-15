<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileTransaction extends Model
{
    //
    protected $table = 'profile_transaction';

    protected $fillable = [
        'profile_id',
        'transaction_id',
        'courses_id',
        'role_id'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // retorna la colección de roles que tiene el usuario dueño del perfil vinculado a esta transacción
    public function availableRoles()
    {
        return $this->profile->user->roles;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
