<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Group;
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
            'due_time' => $this->faker->dateTime(),
            'homework_id' => Homework::factory(),
            'group_id' => Group::factory(),
        ];
    }
}
