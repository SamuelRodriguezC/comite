<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    /**
     * Incorporates types of identity documents
     */
    public function run(): void
    {
        DB::table('documents')->insert([
            ['type' => 'Cédula de Ciudadanía'],
            ['type' => 'Tarjeta de Identidad'],
            ['type' => 'Cédula de Extranjería'],
            ['type' => 'Permiso Especial de Permanencia'],
            ['type' => 'Permiso por Protección Temporal'],
        ]);
    }
}
