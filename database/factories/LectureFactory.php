<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\Lecture;
use App\Models\Subject;
use App\Models\User;

class LectureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lecture::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'day_of_week' => $this->faker->randomNumber(),
            'subject_id' => Subject::factory(),
            'class_room_id' => ClassRoom::factory(),
            'group_id' => Group::factory(),
            'user_id' => User::factory(),
        ];
    }
}
