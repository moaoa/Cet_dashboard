<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'ref_number' => $this->faker->randomNumber(),
            'password' => Hash::make('password'),
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'device_subscriptions' => json_encode([]),
        ];
    }
}
