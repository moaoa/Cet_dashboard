<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class studentSeeders extends Seeder
{

    public function run(): void
    {

        $password = Hash::make('password');

        $names = [
            'علي مصطفى الفورتي',
            'عبدالله عبد الباسط ابوزيد',
            'يوسف علي الورفلي',
            'طارق سليمان الزاوي',
            'أمير سالم العبيدي',
            'خالد فتحي بن غشير',
            'مصطفى عبد الله المصراتي',
            'ياسر حسين الشيباني',
            'محمود خالد العبيدي',
            'حسام عادل الزوي',
            'عمر علي المقريف',
            'حسين إبراهيم الدرسي',
            'ماجد سالم البرغثي',
            'جمال محمد الكريوي',
            'سعيد أحمد الجهاني',
            'رامي فيصل الصويعي',
            'زياد سعد البشتي',
            'ناصر عبد الرحمن المنقوش',
            'فاطمة عائشة الفيتوري',
            'مريم هدى بن عامر',
            'سارة ليلى بن غربية',
            'ليلى أمل القماطي',
            'نور سارة الغرياني',
            'ريما حنان الشركسي',
            'مروة إيمان السويحلي',
            'سلمى رقية الزنتاني',
            'لمى هناء السعداوي',
            'رنا دعاء الفاخري',
            'جميلة ندى الرقيعي',
            'آمنة مريم الترهوني',
            'داليا سارة عقوب',
            'هالة فاطمة العريبي',
            'رحمة أمل الكيلاني',
            'زينب هدى المبروك',
            'لينا ليلى البوعيشي',
            'ميساء إيمان القمودي',
            'روان ندى الجبالي',
            'سمية منى التومي'
        ];
        $counter = 2;
        $phone = 4986954;
        $ref_number = 181130;

        User::create([
            'name' => 'أحمد محمد اقريش',
            'ref_number' => $ref_number,
            'email' => 'graish333@gmail.com',
            'phone_number' => '092' . $phone,
            'password' =>  $password,
            'device_subscriptions' => '[]',

        ]);
        User::create([
            'name' => 'معاذ عمر بن طاهر',
            'ref_number' => ++$ref_number,
            'email' => 'moaadbn3@gmail.com',
            'phone_number' => '092' . $phone + 1,
            'password' =>  $password,
            'device_subscriptions' => '[]',
        ]);

        $students = [];
        foreach ($names as $name) {
            $phone++;
            $counter++;
            $ref_number++;
            $students[] = [
                'name' => $name,
                'ref_number' => $ref_number,
                'email' => 'student' . $counter . '@email.com',
                'phone_number' => '092' . $phone,
                'password' =>  $password,
                'device_subscriptions' => '[]',
            ];
        }
        User::insert($students);
    }
}
