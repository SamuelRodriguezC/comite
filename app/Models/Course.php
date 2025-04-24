<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'course',
        'level',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'level' => 'integer',
    ];

    public function profileTransactions(): HasMany
    {
        return $this->hasMany(ProfileTransaction::class, 'courses_id');
    }
}
