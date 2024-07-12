<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Lecture;
use App\Models\StudentAttendance;

class StudentAttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentAttendance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'lecture_id' => Lecture::factory(),
            'student_id' => ::factory(),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'note' => $this->faker->word(),
            'date' => $this->faker->date(),
        ];
    }
}
