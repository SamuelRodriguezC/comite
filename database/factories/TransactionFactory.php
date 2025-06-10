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
            'status' => 1,
            'created_at' => fake()->dateTimeBetween('-12 months', 'now'), //Fechas aleatorias en 6 meses
        ];
    }
}
