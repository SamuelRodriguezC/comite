<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Document;

class DocumentFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Document::class;

    /**
        * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        return [
            'type' => fake()->word(),
        ];
    }
}
