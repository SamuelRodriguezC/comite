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
     * El nombre del modelo correspondiente de la fábrica.
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Parametros para crear datos de prueba.
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
