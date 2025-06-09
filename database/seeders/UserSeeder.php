<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear los roles si no existen aún
        $roles = ['Estudiante', 'Asesor', 'Evaluador', 'Coordinador', 'Super administrador'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // --------------------- SUPER ADMINISTRADOR ----------------------
        $superAdmin = User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('superadmin'),
        ]);

        $superAdmin->profiles()->create([
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(10000000, 99999999),
            'phone_number' => fake()->numerify('3#########'),
            'level' => 2,
            'document_id' => fake()->numberBetween(1, 3),
        ]);
        $superAdmin->assignRole(['Coordinador', 'Super administrador']);

        // --------------------- COORDINATOR ----------------------
        $coordinator = User::create([
            'name' => 'Coordinador',
            'email' => 'coo@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('coordinador'),
        ]);

        $coordinator->profiles()->create([
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(10000000, 99999999),
            'phone_number' => fake()->numerify('3#########'),
            'level' => 2,
            'document_id' => fake()->numberBetween(1, 3),
        ]);
        $coordinator->assignRole(['Coordinador', 'Estudiante', 'Asesor', 'Evaluador']);

        //--------------------- ADVISOR ----------------------
        $advisor = User::create([
            'name' => 'Asesor',
            'email' => 'ase@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('asesor'),
        ]);

        $advisor->profiles()->create([
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(10000000, 99999999),
            'phone_number' => fake()->numerify('3#########'),
            'level' => fake()->numberBetween(1, 2),
            'document_id' => fake()->numberBetween(1, 3),
        ]);
        $advisor->assignRole('Asesor');

        //--------------------- STUDENT ----------------------
        $student = User::create([
            'name' => 'Estudiante',
            'email' => 'est@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('estudiante'),
        ]);

        $student->profiles()->create([
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(10000000, 99999999),
            'phone_number' => fake()->numerify('3#########'),
            'level' => fake()->numberBetween(1, 2),
            'document_id' => fake()->numberBetween(1, 3),
        ]);
        $student->assignRole('Estudiante');

        //--------------------- EVALUATOR ----------------------
        $evaluator = User::create([
            'name' => 'Evaluador',
            'email' => 'eva@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('evaluador'),
        ]);

        $evaluator->profiles()->create([
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(10000000, 99999999),
            'phone_number' => fake()->numerify('3#########'),
            'level' => fake()->numberBetween(1, 2),
            'document_id' => fake()->numberBetween(1, 3),
        ]);
        //$evaluator->assignRole('Evaluador');
        //$evaluator->assignRole('Asesor');
        $evaluator->assignRole(['Evaluador', 'Asesor']);
    }
}
