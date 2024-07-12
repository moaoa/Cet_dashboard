<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\GroupHomework;
use App\Models\HomeworkGroup;

class GroupHomeworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupHomework::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'homework_id' => $this->faker->numberBetween(-10000, 10000),
            'group_id' => $this->faker->numberBetween(-10000, 10000),
            'due_time' => $this->faker->dateTime(),
            'homework_group_id' => HomeworkGroup::factory(),
        ];
    }
}
