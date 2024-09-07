<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'ref_number' => $this->faker->randomNumber(),
            'type' => $this->faker->randomNumber(),
            'password' => Hash::make('password'),
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
