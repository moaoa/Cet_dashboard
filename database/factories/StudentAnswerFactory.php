<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\QuizQuestion;
use App\Models\StudentAnswer;

class StudentAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentAnswer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'question_id' => $this->faker->numberBetween(-10000, 10000),
            'student_id' => ::factory(),
            'answer' => $this->faker->word(),
            'quiz_question_id' => QuizQuestion::factory(),
        ];
    }
}
