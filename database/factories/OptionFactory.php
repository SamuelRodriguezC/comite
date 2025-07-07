<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Option;

class OptionFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Option::class;

    /**
      * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        return [
            'option' => fake()->word(),
            'level' => fake()->numberBetween(0, 3),
            'component' => fake()->numberBetween(1, 2),
            'description' => fake()->text(),
            'requirement' => fake()->word(),
        ];
    }
}
