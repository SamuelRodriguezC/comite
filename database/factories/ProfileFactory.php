<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(10000000, 99999999),
            'phone_number' => fake()->numerify('3#########'),
            'level' => fake()->numberBetween(1, 2),
            'document_id' => fake()->numberBetween(1, 5),
            'user_id' => User::factory(),
        ];
    }
}
