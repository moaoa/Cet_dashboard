<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\StudentSubject;
use App\Models\Subject;

class StudentSubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentSubject::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'student_id' => ::factory(),
            'passed' => $this->faker->boolean(),
            'note' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
