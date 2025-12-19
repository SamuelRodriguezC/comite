<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRubric extends Model
{
    use HasFactory;

    protected $table = 'rubrics'; // Apunta a la misma tabla que Rubric
    protected $fillable = [
        'name',
        'course_id',
        'performance_level',
        'academic_period',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
