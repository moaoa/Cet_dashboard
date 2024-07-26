<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'moaad',
            'ref_number' => 1111,
            'type' => 1,
            'password' => Hash::make('password'),
            'email' => 'moaadbn3@gmail.com',
            'email_verified_at' => now(),
            'phone_number' => '1222223',
        ]);
    }
}
