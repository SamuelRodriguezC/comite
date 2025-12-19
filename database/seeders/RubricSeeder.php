<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Rubric;

class RubricSeeder extends Seeder
{
    public function run(): void
    {
        // Obtén todos los cursos
        $courses = Course::all();

        foreach ($courses as $course) {
            Rubric::insert([
                [
                    'course_id' => $course->id,
                    'name' => 'Metodología',
                    'description' => 'Evalúa la correcta aplicación de la metodología de investigación.',
                    'course_id' => 1,
                ],
                [
                    'course_id' => $course->id,
                    'name' => 'Marco Teórico',
                    'description' => 'Evalúa la coherencia y pertinencia del marco teórico.',
                    'course_id' => 1,
                ],
                [
                    'course_id' => $course->id,
                    'name' => 'Resultados',
                    'description' => 'Evalúa la claridad y análisis de los resultados obtenidos.',
                    'course_id' => 1,
                ],
                [
                    'course_id' => $course->id,
                    'name' => 'Sustentación',
                    'description' => 'Evalúa la presentación oral y defensa del proyecto.',
                    'course_id' => 1,
                ],
            ]);
            
        }
    }
}
