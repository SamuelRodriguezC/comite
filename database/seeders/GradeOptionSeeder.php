<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Process;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use App\Models\ProfileTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GradeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que existan cursos
        $course1 = Course::find(2);
        $course2 = Course::find(3);
        $course3 = Course::find(4);
        $course4 = Course::find(5);

        // Crear transacción
        $transaction = Transaction::create([
            'component' => 1,
            'option_id' => 11,
            'status' => 1,
            'enabled' => 1,
        ]);

        // Obtener perfiles según usuarios creados en UserSeeder
        $studentProfile   = Profile::whereHas('user', fn($q) => $q->where('email', 'est@gmail.com'))->first();
        $advisorProfile   = Profile::whereHas('user', fn($q) => $q->where('email', 'ase@gmail.com'))->first();
        $evaluatorProfile = Profile::whereHas('user', fn($q) => $q->where('email', 'eva@gmail.com'))->first();
        $coordinatorProfile = Profile::whereHas('user', fn($q) => $q->where('email', 'coo@gmail.com'))->first();

        // Vincular perfiles con roles en la transacción
        if ($studentProfile) {
            ProfileTransaction::create([
                'profile_id'     => $studentProfile->id,
                'transaction_id' => $transaction->id,
                'courses_id'     => $course1->id,
                'role_id'        => 1, // Estudiante
            ]);
        }

        if ($advisorProfile) {
            ProfileTransaction::create([
                'profile_id'     => $advisorProfile->id,
                'transaction_id' => $transaction->id,
                'courses_id'     => $course1->id,
                'role_id'        => 2, // Asesor
            ]);
        }

        if ($evaluatorProfile) {
            ProfileTransaction::create([
                'profile_id'     => $evaluatorProfile->id,
                'transaction_id' => $transaction->id,
                'courses_id'     => $course3->id,
                'role_id'        => 3, // Evaluador
            ]);
        }

        if ($coordinatorProfile) {
            ProfileTransaction::create([
                'profile_id'     => $coordinatorProfile->id,
                'transaction_id' => $transaction->id,
                'courses_id'     => $course4->id,
                'role_id'        => 3, // Evaluador
            ]);
        }

        $stages = [1, 2];
            foreach ($stages as $stage) {
                Process::factory()->create([
                    'transaction_id' => $transaction->id,
                    'requirement' => '',
                    'state' => 3,
                    'comment' => '',
                    'completed' => false,
                    'delivery_date' => null,
                    'transaction_id' => $transaction->id,
                    'stage_id' => $stage,
                ]);
            }
    }
}
