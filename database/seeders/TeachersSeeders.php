<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeachersSeeders extends Seeder
{

    public function run(): void
    {

        $password = Hash::make('password');

        $teachers = [
            'م.عبد الحميد الواعر',
            'أ.مراد',
            'م.هدى جمعة',
            'د.ناصر ضياف',
            'ا.زهره الاشعل',
            'ا.عبد سلام القطاوي',
            'ا.كرم نصار',
            'ا .احمد بن مصطفى',
            'ا.ناجي عبد الرحمن',
            'ا.عبد حكيم قجة',
            'ا.محمد الفرجاني',
            'ا.زهرة الأشعل',
            'د.ظافر خليل',
            'د.عبد القادر الأمين',
            'د.محمد شرود',
            'د.حافظ البوعيشي',
            'د.ضياء مزوغي',
            'د.طارق الشويهدي',
            'م.مصطفى الطاهر',
            'د.نوري ميلادي',
            'د.صلاح العيدودي',
            'ربيع شويهدي'
        ];

        $counter = 2;
        $phone = 8795421;
        $ref_number = 175489;
        Teacher::create([
            'name' => 'أ.مصطفى قاباج',
            'ref_number' => $ref_number,
            'email' => 'graish333@gmail.com',
            'phone_number' => '092' . $phone,
            'password' =>  $password,
            'device_subscriptions' => '[]',

        ]);
        Teacher::create([
            'name' => 'أ.عادل اجديدو',
            'ref_number' => ++$ref_number,
            'email' => 'moaadbn3@gmail.com',
            'phone_number' => '092' . $phone + 1,
            'password' =>  $password,
            'device_subscriptions' => '[]',
        ]);

        $teachers = [];

        foreach ($teachers as $teacher) {
            $phone++;
            $counter++;
            $ref_number++;
            $teachers[] = [
                'name' => $teacher,
                'ref_number' => $ref_number,
                'email' => 'student' . $counter . '@email.com',
                'phone_number' => '092' . $phone,
                'password' =>  $password,
                'device_subscriptions' => '[]',
            ];
        }

        Teacher::insert($teachers);
    }
}
