<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class SubjectTeacherSeeder extends Seeder
{

    public function run(): void
    {

        $ghabaj = Teacher::where('name', 'أ.مصطفى قاباج')->first();

        $ghabajSubject1 = Subject::where('name', 'C++ برمجة بلغة')->first();
        $ghabajSubject2 = Subject::where('name', 'صيانة حاسب الي')->first();        
        $ghabajSubject3 = Subject::where('name', 'مهارات دراسية')->first();        

        $adel = Teacher::where('name', 'أ.عادل اجديدو')->first();

        $adelSubject1 = Subject::where('name', 'أساسيات برمجة')->first();
        $adelSubject2 = Subject::where('name', 'دوائر رقمية 1')->first();        

        $ghabajSubject1->teachers()->attach($ghabaj);
        $ghabajSubject2->teachers()->attach($ghabaj);
        $ghabajSubject3->teachers()->attach($ghabaj);

        $adelSubject1->teachers()->attach($adel);
        $adelSubject2->teachers()->attach($adel);
        

    }
}
