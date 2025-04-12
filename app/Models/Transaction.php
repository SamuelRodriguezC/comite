<?php

namespace App\Models;

use App\Models\Option;
use App\Models\Process;
use App\Models\Profile;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->belongsToMany(Profile::class);
    }
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
    public function processes(): HasMany
    {
        return $this->hasMany(Process::class);
    }
}
