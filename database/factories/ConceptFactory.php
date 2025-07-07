<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Concept;

class ConceptFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Concept::class;

    /**
     * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        return [
            'concept' => fake()->word(),
        ];
    }
}
