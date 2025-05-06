<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // o Hash::make()
            'remember_token' => Str::random(10),
        ];
    }

    // ---------- ASIGNAR ROL A CADA USUARIO ----------
    public function configure()
    {
    return $this->afterCreating(function (User $user) {
        // Obtener nombres de los roles disponibles
        $roles = Role::where('id', '!=', 5)->pluck('id')->toArray(); //Deshabilitar rol Super Admin al crear usuarios
        // $roles = Role::whereNotIn('id', ['coordinator', 'student'])->pluck('name')->toArray(); //Deshabilitar uno o mas roles a ejecutar la factory crear usuarios

        if (!empty($roles)) {
            // Escoge uno aleatorio y lo asigna
            $user->assignRole(fake()->randomElement($roles));
        }
    });
    }
}
