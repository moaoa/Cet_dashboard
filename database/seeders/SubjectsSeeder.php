<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{

    public function run(): void
    {

        $semester1Subjects = [
            'فيزياء',
            'دوائر رقمية 1',
            'دوائر كهربائية 1',
            'انجليزي 1',
            'رياضة 1',
        ];
    
        // Subjects for Semester 2
        $semester2Subjects = [
            'رياضة 2',
            'دوائر كهربائية 2',
            'دوائر كهربائية معمل',
            'أساسيات برمجة',
            'دوائر رقمية 2',
            'دوائر رقمية معمل',
            'ألكترونية 1',
            'انجليزي 2',
        ];

        $computerSubjects = [
            'C++ برمجة بلغة ',
            'مهارات دراسية',
            'احصاء',
            'ألكترونية 2',
            'الكترونية معمل',
            'أنجليزي 3',
            'صيانة حاسب الي',
        ];
    

        $sem1 = Semester::where('order', 1)->where('major', 0)->first(); //للسيمستر الالول
        $sem2 = Semester::where('order', 2)->where('major', 0)->first();//للسيمستر الثاني
        $sem3 = Semester::where('order', 3)->where('major', 1)->first();//للسيمستر الثالث تخصص الحاسب


        foreach ($semester1Subjects as $subject) {
            Subject::create([
                'name' => $subject,
                'semester_id' => $sem1->id,
            ]);
        }
        foreach ($semester2Subjects as $subject) {
            Subject::create([
                'name' => $subject,
                'semester_id' => $sem2->id,
            ]);
        }
        foreach ($computerSubjects as $subject) {
            Subject::create([
                'name' => $subject,
                'semester_id' => $sem3->id,
            ]);
        }
    }
}
