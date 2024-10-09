<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GroupsSeeder extends Seeder
{

    public function run(): void
    {
        $teachers1 = [1, 2, 3, 1, 2, 1];

        $teachers2 = [1, 2, 3,  1, 2];

        $ghabaj = Teacher::where('name', 'أ.مصطفى قاباج')->first();
        foreach ($teachers1 as $group) {
            Group::create([
                'name' => $group,
                'teacher_id' => $ghabaj->id,
            ]);
        }

        $adel = Teacher::where('name', 'أ.عادل اجديدو')->first();
        foreach ($teachers2 as $group) {
            Group::create([
                'name' => $group,
                'teacher_id' => $adel->id,
            ]);
        }
    }
}
