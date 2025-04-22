<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Option;
use App\Models\Transaction;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'component' => fake()->numberBetween(1, 2),
            'enabled' => fake()->numberBetween(1, 2),
            'option_id' => fake()->numberBetween(1, 17),
        ];
    }
}
