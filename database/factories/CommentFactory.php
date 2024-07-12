<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Homework;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraphs(3, true),
            'homework_id' => Homework::factory(),
            'student_id' => $this->faker->numberBetween(-10000, 10000),
            'teacher_id' => $this->faker->numberBetween(-10000, 10000),
            'commentable_id' => $this->faker->numberBetween(-10000, 10000),
            'commentable_type' => $this->faker->word(),
        ];
    }
}
