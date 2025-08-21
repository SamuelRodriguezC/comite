<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Seccional;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Signer>
 */
class SignerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'faculty' => $this->faker->word,
            'seccional' => $this->faker->randomElement(Seccional::cases()),
            'signature' => 'signatures/directorsignature.png',
        ];
    }
}
