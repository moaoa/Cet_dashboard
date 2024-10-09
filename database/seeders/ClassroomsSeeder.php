<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class ClassroomsSeeder extends Seeder
{

    public function run(): void
    {
        $classrooms = [
            'القاعة 300',
            'القاعة 315',
            'القاعة 320',
            'القاعة 335',
            'القاعة 310',
            'القاعة 220',
            'القاعة 222',
            'القاعة 165',
            'القاعة 215',
            'معمل سيسكو',
            'معمل الكهربائية',
            'معمل سيسكو 2',
        ];
        foreach ($classrooms as $classroom) {
            ClassRoom::create([
                'name' => $classroom,
            ]);
        }
    }
}
