<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Semester;

class SemesterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Semester::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'major' => $this->faker->randomNumber(),
        ];
    }
}
