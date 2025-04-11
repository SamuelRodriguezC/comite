<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Certificate;
use App\Models\Transaction;

class CertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Certificate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'acta' => fake()->word(),
            'comment' => fake()->text(),
            'resolution' => fake()->numberBetween(-8, 8),
            'transaction_id' => Transaction::factory(),
        ];
    }
}
