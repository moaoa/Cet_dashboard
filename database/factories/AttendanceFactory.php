<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Attendance;
use App\Models\Lecture;
use App\Models\User;

class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomNumber(),
            'note' => $this->faker->word(),
            'date' => $this->faker->date(),
            'lecture_id' => Lecture::factory(),
            'user_id' => User::factory(),
        ];
    }
}
