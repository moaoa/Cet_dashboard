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
            'order' => $this->faker->numberBetween(-10000, 10000),
            'name' => $this->faker->name(),
            'major' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
