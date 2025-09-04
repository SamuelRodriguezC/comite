<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'file_path',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
