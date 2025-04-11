<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Document;
use App\Models\Profile;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numberBetween(-100000, 100000),
            'phone_number' => fake()->phoneNumber(),
            'level' => fake()->numberBetween(-8, 8),
            'document_id' => Document::factory(),
        ];
    }
}
