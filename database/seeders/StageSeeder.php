<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StageSeeder extends Seeder
{
    /**
     * Incorporates the stages of the transaction
     */
    public function run(): void
    {
        DB::table('stages')->insert([
            ['stage' => 'Solicitud'],
            ['stage' => 'Entrega'],
            ['stage' => 'Primera corrección'],
            ['stage' => 'Segunda correción'],
            ['stage' => 'Finalizado'],
            ['stage' => 'Cancelado'],
            ['stage' => 'Aplazado'],
        ]);
    }
}
