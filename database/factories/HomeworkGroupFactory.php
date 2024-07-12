<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Homework;
use App\Models\HomeworkGroup;

class HomeworkGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HomeworkGroup::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'homework_id' => Homework::factory(),
            'group_id' => ::factory(),
            'due_time' => $this->faker->dateTime(),
        ];
    }
}
