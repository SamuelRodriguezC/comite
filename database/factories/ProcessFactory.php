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
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Process::class;

    /**
     * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        return [
            'requirement' => fake()->word(),
            'state' => 3,
            'comment' => fake()->text(),
            'transaction_id' => Transaction::factory(),
            'completed' => false,
            'stage_id' => fake()->numberBetween(1, 4),
        ];
    }
}
