<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = Transaction::all();
        $coursesByLevel = Course::all()->groupBy('level');

        // Asumimos IDs de roles: puedes ajustar estos valores según tu base de datos
        $ROLE_STUDENT = 1;
        $ROLE_EVALUATOR = 3;
        $ROLE_ADVISOR = 2;

        // Cargar perfiles por rol
        $students = $this->getProfilesByRole($ROLE_STUDENT);
        $evaluators = $this->getProfilesByRole($ROLE_EVALUATOR);
        $advisors = $this->getProfilesByRole($ROLE_ADVISOR);

        foreach ($transactions as $transaction) {
            // Asignar de 1 a 2 estudiantes
            $this->assignProfiles($transaction, $students, $coursesByLevel, $ROLE_STUDENT, rand(1, 2));

            // Asignar de 1 a 2 evaluadores
            $this->assignProfiles($transaction, $evaluators, $coursesByLevel, $ROLE_EVALUATOR, rand(1, 2));

            // Asignar 1 asesor
            $this->assignProfiles($transaction, $advisors, $coursesByLevel, $ROLE_ADVISOR, 1);
        }
    }

    private function getProfilesByRole(int $roleId)
    {
        return Profile::whereIn('user_id', function ($query) use ($roleId) {
            $query->select('model_id')
                ->from('model_has_roles')
                ->where('role_id', $roleId)
                ->where('model_type', 'App\\Models\\User');
        })->get();
    }

    private function assignProfiles($transaction, $profiles, $coursesByLevel, $roleId, $count)
    {
        $assigned = 0;

        foreach ($profiles->shuffle() as $profile) {
            if (!isset($coursesByLevel[$profile->level])) {
                continue;
            }

            // Validación estricta: evitar duplicados con diferente curso
            $alreadyAssigned = DB::table('profile_transaction')
                ->where('profile_id', $profile->id)
                ->where('transaction_id', $transaction->id)
                ->exists();

            if ($alreadyAssigned) {
                continue;
            }

            $course = $coursesByLevel[$profile->level]->random();

            $profile->transactions()->attach($transaction->id, [
                'courses_id' => $course->id,
            ]);

            $assigned++;

            if ($assigned >= $count) {
                break;
            }
        }
    }
}
