<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Homework;
use App\Models\HomeworkUserAnswer;
use App\Models\User;

class HomeworkUserAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HomeworkUserAnswer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
            'user_id' => User::factory(),
            'homework_id' => Homework::factory(),
        ];
    }
}
