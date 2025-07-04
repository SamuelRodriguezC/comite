<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Option;

class OptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Option::class;

    /**
     * Define the model's default state.
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
