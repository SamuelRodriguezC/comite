<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Certificate;
use App\Models\Transaction;

class CertificateFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Certificate::class;

    /**
     * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        return [
            'acta' => fake()->word(),
            'comment' => fake()->text(),
            'resolution' => fake()->numberBetween(0, 4),
            'transaction_id' => Transaction::factory(),
        ];
    }
}
