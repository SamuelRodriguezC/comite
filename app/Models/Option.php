<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'option',
        'level',
        'component',
        'description',
        'requeriment',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'level' => 'integer',
        'component' => 'integer',
    ];

    /**
     * Establishes the type of relationship it has with other models
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
