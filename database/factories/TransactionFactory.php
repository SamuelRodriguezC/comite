<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Option;
use App\Models\Transaction;

class TransactionFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Parametros para crear datos de prueba.
     */
    public function definition(): array
    {
        // Paso 1: Escoger componente aleatoriamente (1 o 2)
        $component = fake()->numberBetween(1, 2);

        // Paso 2: Obtener IDs de opciones que tengan ese componente
        $optionIds = \App\Models\Option::where('component', $component)->pluck('id');

        // Paso 3: Elegir aleatoriamente un option_id entre los resultados filtrados
        $optionId = $optionIds->isNotEmpty() ? fake()->randomElement($optionIds->toArray()) : null;

        return [
            'component' => $component,
            'enabled' => fake()->numberBetween(1, 2),
            'option_id' => $optionId,
            'status' => fake()->numberBetween(1, 5),
            'created_at' => fake()->dateTimeBetween('-12 months', 'now'), //Fechas aleatorias en 6 meses
        ];
    }
}
