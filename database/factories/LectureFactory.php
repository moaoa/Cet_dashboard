<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassRoomGroupTeacher;
use App\Models\Lecture;
use App\Models\Subject;

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
            'day_of_week' => $this->faker->word(),
            'subject_id' => Subject::factory(),
            'class_room_group_teacher_id' => ClassRoomGroupTeacher::factory(),
        ];
    }
}
