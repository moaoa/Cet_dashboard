<?php
namespace Database\Seeders;

use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupsSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        // Retrieve teachers
        $ghabaj = Teacher::where('name', 'أ.مصطفى قاباج')->first();
        $adel = Teacher::where('name', 'أ.عادل اجديدو')->first();

        // Retrieve groups for the first subject
        $group1 = Group::where('name', '1')->where('teacher_id', $ghabaj->id)->first();
        $group2 = Group::where('name', '2')->where('teacher_id', $ghabaj->id)->first();
        $group3 = Group::where('name', '3')->where('teacher_id', $ghabaj->id)->first();

        // Associate groups with the first subject
        $subject1 = Subject::where('name', 'C++ برمجة بلغة')->first();
        $subject1->groups()->attach([$group1->id, $group2->id, $group3->id]);

        // Retrieve groups for the second subject
        $group1_2 = Group::where('name', '1')->where('teacher_id', $ghabaj->id)->offset(1)->limit(1)->first();
        $group2_2 = Group::where('name', '2')->where('teacher_id', $ghabaj->id)->offset(1)->limit(1)->first();

        // Associate groups with the second subject
        $subject2 = Subject::where('name', 'صيانة حاسب الي')->first();
        $subject2->groups()->attach([$group1_2->id, $group2_2->id]);

        // Retrieve groups for the third subject
        $group1_3 = Group::where('name', '1')->where('teacher_id', $ghabaj->id)->offset(2)->limit(1)->first();

        // Associate groups with the third subject
        $subject3 = Subject::where('name', 'مهارات دراسية')->first();
        $subject3->groups()->attach([$group1_3->id]);

        // Retrieve groups for the fourth subject
        $adelgroup1 = Group::where('name', '1')->where('teacher_id', $adel->id)->first();
        $adelgroup2 = Group::where('name', '2')->where('teacher_id', $adel->id)->first();
        $adelgroup3 = Group::where('name', '3')->where('teacher_id', $adel->id)->first();

        // Associate groups with the fourth subject
        $subject1_2 = Subject::where('name', 'أساسيات برمجة')->first();
        $subject1_2->groups()->attach([$adelgroup1->id, $adelgroup2->id, $adelgroup3->id]);

        // Retrieve groups for the fifth subject
        $adelgroup1_2 = Group::where('name', '1')->where('teacher_id', $adel->id)->offset(1)->limit(1)->first();
        $adelgroup2_2 = Group::where('name', '2')->where('teacher_id', $adel->id)->offset(1)->limit(1)->first();

        // Associate groups with the fifth subject
        $subject2_1 = Subject::where('name', 'دوائر رقمية 1')->first();
        $subject2_1->groups()->attach([$adelgroup1_2->id, $adelgroup2_2->id]);
    }
}