<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Homework;
use App\Models\Subject;
use App\Models\User;

class HomeworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Homework::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'attachments' => json_encode([
                'name' => 'Homework II.png',
                'url' => 'https://www.halpernadvisors.com/wp-content/uploads/2022/09/HW.jpg'
            ]),
            'user_id' => User::factory(),
            'subject_id' => Subject::factory(),
        ];
    }
}
