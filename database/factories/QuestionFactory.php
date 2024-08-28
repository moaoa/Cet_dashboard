<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\Question;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->word(),
            'answer' => $this->faker->word(),
            'type' => $this->faker->randomNumber(),
            'quiz_id' => Quiz::factory(),
        ];
    }
}
