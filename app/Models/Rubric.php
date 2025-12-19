<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rubric extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'course_id', // <- necesario
        'competencies_results_grades',
        'performance_level',
        'level_descriptions',
        'resultados_aprendizaje',
        'academic_period',
        'description',
        'status', // si quieres usar toggleStatus
    ];

    // Relación con Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
