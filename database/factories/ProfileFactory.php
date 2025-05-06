<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'level' => fake()->numberBetween(1, 2),
            'document_id' => fake()->numberBetween(1, 5),
            'user_id' => User::class(),
        ];
    }
}
