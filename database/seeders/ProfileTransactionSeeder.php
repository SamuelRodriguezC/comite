<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\Course;

class ProfileTransactionSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los perfiles, transacciones y cursos
        $profiles = Profile::all();
        $transactions = Transaction::all();
        $courses = Course::all();

        // Asegúrate de que haya al menos un perfil, transacción y curso
        if ($profiles->isEmpty() || $transactions->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No hay suficientes datos para hacer las asociaciones.');
            return;
        }

        // Crear asociaciones de forma aleatoria
        foreach ($profiles as $profile) {
            $randomTransactions = $transactions->random(rand(1, 3));
            $randomCourses = $courses->random(rand(1, 3));

            foreach ($randomTransactions as $transaction) {
                foreach ($randomCourses as $course) {
                    // Verificar si ya existe esta combinación
                    $exists = DB::table('profile_transaction')->where([
                        ['profile_id', '=', $profile->id],
                        ['transaction_id', '=', $transaction->id],
                    ])->exists();

                    if (!$exists) {
                        DB::table('profile_transaction')->insert([
                            'profile_id' => $profile->id,
                            'transaction_id' => $transaction->id,
                            'courses_id' => $course->id,
                        ]);
                    }
                }
            }
        }

        // $this->command->info('Seeding de perfil-transacción completado.');
    }
}
