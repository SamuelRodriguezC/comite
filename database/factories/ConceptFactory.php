<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Concept;

class ConceptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Concept::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'concept' => fake()->word(),
        ];
    }
}
