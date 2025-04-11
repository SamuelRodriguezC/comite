<?php

namespace Database\Seeders;

use App\Models\Concept;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('concepts')->insert([
            ['concept' => 'Aprobado'],
            ['concept' => 'No aprobado'],
        ]);
    }
}
