<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('roles')->insert([
            ['name' => 'Estudiante', 'guard_name' => 'web'],
            ['name' => 'Asesor', 'guard_name' => 'web'],
            ['name' => 'Evaluador', 'guard_name' => 'web'],
            ['name' => 'Coordinador', 'guard_name' => 'web'],
            ['name' => 'Super administrador', 'guard_name' => 'web'],
        ]);
    }
}
