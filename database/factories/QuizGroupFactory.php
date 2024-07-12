<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Group;
use App\Models\QuizGroup;

class QuizGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizGroup::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'quiz_id' => ::factory(),
            'end_time' => $this->faker->dateTime(),
            'start_time' => $this->faker->dateTime(),
        ];
    }
}
