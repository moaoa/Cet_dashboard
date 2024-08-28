<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Question;
use App\Models\User;
use App\Models\UserAnswer;

class UserAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAnswer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'answer' => $this->faker->word(),
            'question_id' => Question::factory(),
            'user_id' => User::factory(),
        ];
    }
}
