<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentsSubjectsSeeder extends Seeder
{

    public function run(): void
    {
        $ahmed = User::where('name', 'أحمد محمد اقريش')->first();
        $moaad = User::where('name', 'معاذ عمر بن طاهر')->first();

        $users1 = User::skip(2)->take(5)->get();
        $users2 = User::skip(7)->take(5)->get();
        $users3 = User::skip(12)->take(6)->get();

        $subject1 = Subject::where('name','C++ برمجة بلغة')->first();
        $subject2 = Subject::where('name','صيانة حاسب الي')->first();
        $subject3 = Subject::where('name','مهارات دراسية')->first();
        $subject4 = Subject::where('name','أساسيات برمجة')->first();
        $subject5 = Subject::where('name','دوائر رقمية 1')->first();

        $ahmed->subjects()->attach([$subject1->id, $subject2->id, $subject3->id, $subject4->id, $subject5->id]);
        $moaad->subjects()->attach([$subject1->id, $subject2->id, $subject3->id, $subject4->id, $subject5->id]);

        $users1->each(function ($user) use ($subject1, $subject2, $subject3, $subject4, $subject5) {
            $user->subjects()->attach([$subject1->id, $subject2->id, $subject3->id, $subject4->id, $subject5->id]);
        });
        $users2->each(function ($user) use ($subject1, $subject2, $subject3, $subject4, $subject5) {
            $user->subjects()->attach([$subject1->id, $subject2->id, $subject3->id, $subject4->id, $subject5->id]);
        });
        $users3->each(function ($user) use ($subject1, $subject2, $subject3, $subject4, $subject5) {
            $user->subjects()->attach([$subject1->id, $subject2->id, $subject3->id, $subject4->id, $subject5->id]);
        });
        
    }
}
