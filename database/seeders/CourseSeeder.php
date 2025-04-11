<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * It includes university courses with their respective academic level.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            ['course' => 'Ingeniería Ambiental', 'level' => '1'],
            ['course' => 'Ingeniería en Ciencia de Datos', 'level' => '1'],
            ['course' => 'Ingeniería Industrial', 'level' => '1'],
            ['course' => 'Ingeniería Mecánica', 'level' => '1'],
            ['course' => 'Ingeniería de Sistemas', 'level' => '1'],
            ['course' => 'Especialización en Gerencia Ambiental', 'level' => '2'],
            ['course' => 'Especialización en Gerencia de Calidad de Productos y Servicios', 'level' => '2'],
            ['course' => 'Especialización en Gerencia de Mercadeo y Estrategia de Ventas', 'level' => '2'],
            ['course' => 'Maestría en Ingeniería', 'level' => '2'],
        ]);
    }
}
