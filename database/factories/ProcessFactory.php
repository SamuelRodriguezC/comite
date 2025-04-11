<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Process;
use App\Models\Stage;
use App\Models\Transaction;

class ProcessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Process::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'requeriment' => fake()->word(),
            'state' => fake()->numberBetween(-8, 8),
            'comment' => fake()->text(),
            'transaction_id' => Transaction::factory(),
            'stage_id' => Stage::factory(),
        ];
    }
}
