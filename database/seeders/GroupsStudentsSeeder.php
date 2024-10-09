<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupsStudentsSeeder extends Seeder
{

    public function run(): void
    {

        $ghabaj = Teacher::where('name', 'أ.مصطفى قاباج')->first();
        $adel = Teacher::where('name', 'أ.عادل اجديدو')->first();

        $ahmed = User::where('name', 'أحمد محمد اقريش')->first();
        $moaad = User::where('name', 'معاذ عمر بن طاهر')->first();


        $group1 = Group::where('name', '1')->where('teacher_id', $ghabaj->id)->first(); //ahmed
        $group2 = Group::where('name', '2')->where('teacher_id', $ghabaj->id)->first(); //moaad
        $group3 = Group::where('name', '3')->where('teacher_id', $ghabaj->id)->first(); //users


        $users1 = User::skip(2)->take(5)->get();
        $users2 = User::skip(7)->take(5)->get();
        $users3 = User::skip(12)->take(6)->get();

        $group1->users()->attach($ahmed);
        $group1->users()->attach($users1);


        $group2->users()->attach($moaad);
        $group2->users()->attach($users2);


        $group3->users()->attach($users3);

        //تعبئة المجموعات لمادة 1 ghbaj
        //-------------------------------------------------------


        $group1_2 = Group::where('name', '1')->where('teacher_id', $ghabaj->id)->offset(1)->limit(1)->first(); //ahmed
        $group2_2 = Group::where('name', '2')->where('teacher_id', $ghabaj->id)->offset(1)->limit(1)->first(); //moaad

        $group1_2->users()->attach($ahmed);
        $group1_2->users()->attach($users1);


        $group2_2->users()->attach($moaad);
        $group2_2->users()->attach($users2);

        //تعبئة المجموعات لمادة 2 ghbaj
        //-------------------------------------------------------

        $group1_3 = Group::where('name', '1')->where('teacher_id', $ghabaj->id)->offset(2)->limit(1)->first(); //ahmed

        $group1_3->users()->attach($ahmed);
        $group1_3->users()->attach($moaad);
        $group1_3->users()->attach($users1);



        //تعبئة المجموعات لمادة 3 ghbaj
        //-------------------------------------------------------





        $adelgroup1 = Group::where('name', '1')->where('teacher_id', $adel->id)->first(); //ahmed
        $adelgroup2 = Group::where('name', '2')->where('teacher_id', $adel->id)->first(); //moaad
        $adelgroup3 = Group::where('name', '3')->where('teacher_id', $adel->id)->first(); //users


        $adelgroup1->users()->attach($ahmed);
        $adelgroup1->users()->attach($users1);


        $adelgroup2->users()->attach($moaad);
        $adelgroup2->users()->attach($users2);

        $adelgroup3->users()->attach($users3);

        //تعبئة المجموعات لمادة 1 adel
        //-------------------------------------------------------


        $adelgroup1_2 = Group::where('name', '1')->where('teacher_id', $adel->id)->offset(1)->limit(1)->first(); //ahmed
        $adelgroup2_2 = Group::where('name', '2')->where('teacher_id', $adel->id)->offset(1)->limit(1)->first(); //moaad

        $adelgroup1_2->users()->attach($ahmed);
        $adelgroup1_2->users()->attach($users1);


        $adelgroup2_2->users()->attach($moaad);
        $adelgroup2_2->users()->attach($users2);



        //تعبئة المجموعات لمادة 2 ghbaj
        //-------------------------------------------------------

    }
}
