<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Stage;

class StageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Stage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stage' => fake()->word(),
        ];
    }
}
