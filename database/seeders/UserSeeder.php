<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {


        // --------------------- COORDINATOR ----------------------
        $coordinator = User::factory()->create([
            'name' => 'Coordinador',
            'email' => 'coo@gmail.com',
            'password' => bcrypt('coordinador'),
        ]);

        $coordinator->profiles()->create([
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(10000000, 99999999),
            'phone_number' => fake()->numerify('3#########'),
            'level' => fake()->numberBetween(1, 2),
            'document_id' => fake()->numberBetween(1, 3),
        ]);

        //--------------------- ADVISOR ----------------------
        $advisor = User::factory()->create([
            'name' => 'Asesor',
            'email' => 'ase@gmail.com',
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

        //--------------------- STUDENT ----------------------
        $student = User::factory()->create([
            'name' => 'Estudiante',
            'email' => 'est@gmail.com',
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

        //--------------------- EVALUATOR ----------------------
        $evaluator = User::factory()->create([
            'name' => 'Evaluador',
            'email' => 'eva@gmail.com',
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
    }
}
