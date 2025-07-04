<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Concept;
use App\Models\Process;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'comment' => fake()->text(),
            'process_id' => Process::factory(),
            'concept_id' => Concept::factory(),
        ];
    }
}
