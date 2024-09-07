<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubject;

class UserSubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSubject::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'passed' => $this->faker->boolean(),
            'subject_id' => Subject::factory(),
            'user_id' => User::factory(),
        ];
    }
}
