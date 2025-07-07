<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Stage;

class StageFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Stage::class;

    /**
     * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        return [
            'stage' => fake()->word(),
        ];
    }
}
