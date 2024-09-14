<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\AttendanceStatus;
use App\Enums\UserType;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Homework;
use App\Models\Lecture;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'moaad',
            'ref_number' => 1111,
            'type' => UserType::Admin,
            'email' => 'moaadbn3@gmail.com',
            'email_verified_at' => now(),
            'phone_number' => '1222223',
        ]);
      // Seed Semesters
        Semester::factory()->count(3)->create();

        // Seed Subjects
        //Subject::factory()->count(10)->create();
        $subjects = Subject::factory()->createMany([
            ['name' => 'كهربائية 1'],
            ['name' => 'كهربائية 2'],
            ['name' => 'رياضة 1'],
            ['name' => 'رياضة 2'], // You can add more if needed
        ]);

        // Seed ClassRooms
        ClassRoom::factory()->count(5)->create();

        //Group::factory()->count(2)->create();
        Group::factory()->create([
            'name' => '1'
        ]);

        Group::factory()->create([
            'name' => '2'
        ]);

        //Homework::factory()->count(2)->create();

        Homework::factory()->create([
            'name' => 'واجب رقم 1',
            'description' => 'السلام عليكم جميعا للطلبة المطالَبين بواجبات أو عروض. السبت القادم سيكون آخر فرصة إن شاء الله فالرجاء منكم الالتزام بتقديم واجباتكم. وبالتوفيق',
            'attachments' => json_encode([
                'name' => 'Homework II.png',
                'url' => 'https://www.halpernadvisors.com/wp-content/uploads/2022/09/HW.jpg'
            ]),
        ]);
        Homework::factory()->create([
            'name' => 'واجب رقم 2',
            'description' => 'السلام عليكم جميعا للطلبة الذين لم يقوموا بعمل اختبار ثاني أو من يريد اختبار تعويضي سيكون هناك اختبار أخير يوم السبت القادم إن شاء الله في الدرس الماضي (البيانات الضخمة) سيكون توقيت الاختبار قبل الصلاة الظهر وكذلك في بداية المحاضرة سأطلب من بعض الطلبة أن يجيبوا شفويا على أسئلة متعلقة بآخر درس (لدرجات المشاركة) حنستهدف بالتحديد الطلبة الذين لم يشاركوا كثيرا خلال الفصل وسأقوم إن شاء الله بتسليم أوراق الامتحان النصفي لكم في نهاية المحاضرة',
            'attachments' => json_encode([
                'name' => 'Homework II.png',
                'url' => 'https://www.halpernadvisors.com/wp-content/uploads/2022/09/HW.jpg'
            ]),
        ]);

        // Seed Groups
        $group = Group::first();
        $classRoom = ClassRoom::first();
        $subject = Subject::first();
        $semester = Semester::first();
        $teacher = Teacher::factory()->create([
            'name' => 'رحيم',
            'email' => 'raheemdehom123@gmail.com',
        ]);

        $subject->groups()->attach($group);

        // Create a new Lecture instance and associate it with the first instances of the related models
        $lecture = Lecture::create([
            'start_time' => '2023-08-01 10:00:00',
            'end_time' => '2023-08-01 12:00:00',
            'day_of_week' => 1,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'teacher_id' => 1, // Assuming there's a user with ID 1
        ]);

        Lecture::create([
            'start_time' => '2023-08-01 12:00:00',
            'end_time' => '2023-08-01 15:00:00',
            'day_of_week' => 2,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'teacher_id' => 1, // Assuming there's a user with ID 1
        ]);

        Lecture::create([
            'start_time' => '2023-08-01 15:00:00',
            'end_time' => '2023-08-01 18:00:00',
            'day_of_week' => 2,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'teacher_id' => 1, // Assuming there's a user with ID 1
        ]);

        $subject2 = Subject::create([
            'name' => 'كهربائية 3',
            'semester_id' => 2,
        ]);

        $subject2->groups()->attach($group);

        Lecture::create([
            'start_time' => '2023-08-01 5:00:00',
            'end_time' => '2023-08-01 14:00:00',
            'day_of_week' => 3,
            'subject_id' => $subject2->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'teacher_id' => 1, // Assuming there's a user with ID 1
        ]);



        $subject->teachers()->attach($teacher);

        $group = Group::first();

        $tomorrow = Carbon::tomorrow()->toDateTimeString();

        // Create a new Homework instance and associate it with the first instances of the related models
        $homework = Homework::create([
            'name' => 'واجب رياضيات',
            'description' => 'حل مسائل الجبر من الصفحة 25 إلى 30',
            'attachments' => json_encode([[
                'name' => 'Homework II.png',
                'url' => 'https://www.halpernadvisors.com/wp-content/uploads/2022/09/HW.jpg'
            ]]),
            'user_id' => $teacher->id,
            'subject_id' => $subject->id,
        ]);
        // Associate the Homework with the first Group
        $homework->groups()->attach($group->id, ['due_time' => $tomorrow]);

        $homework = Homework::create([
            'name' => 'واجب رياضيات',
            'description' => 'حل مسائل الجبر من الصفحة 25 إلى 30',
            'attachments' => json_encode([[
                'name' => 'Homework II.png',
                'url' => 'https://www.halpernadvisors.com/wp-content/uploads/2022/09/HW.jpg'
            ]]),
            'user_id' => $teacher->id,
            'subject_id' => $subject->id,
        ]);
        // Associate the Homework with the first Group
        $homework->groups()->attach($group->id, ['due_time' => $tomorrow]);

        $homework = Homework::create([
            'name' => 'بحث عن النباتات',
            'description' => 'كتابة تقرير عن أنواع النباتات وفوائدها.',
            'attachments' => json_encode([[
                'name' => 'Homework II.png',
                'url' => 'https://www.halpernadvisors.com/wp-content/uploads/2022/09/HW.jpg'
            ]]),
            'user_id' => $teacher->id,
            'subject_id' => $subject->id,
        ]);
        // Associate the Homework with the first Group
        $homework->groups()->attach($group->id, ['due_time' => $tomorrow]);

        $student = User::factory()->create([
            'name' => 'ahmad',
            'ref_number' => 2222,
            'type' => UserType::Student,
            'email' => 'graish333@gmail.com',
            'email_verified_at' => now(),
            'phone_number' => '1222223',
        ]);

        $student->groups()->attach($group);

        $student->subjects()->attach($subjects->pluck('id'), ['passed' => false]);

        // Now create the comment
        $comments = Comment::create([
            'content' => '>_< gggggggg',
            'commentable_id' => $student->id,
            'commentable_type' => User::class,
            'homework_id' => $homework->id
        ]);

        $quiz = Quiz::create([
            'user_id' => $teacher->id,
            'name' => 'الاختبار رقم 1',
            'subject_id' => $subject->id,
            'note' => 'التسليم يوم 30 تسعة >_<تن'
        ]);

        $quiz->groups()->attach($group, [
            'start_time' => now(),
            'end_time' => Carbon::now()->addMinute(5),
        ]);

        $questions = [
            [
                'question' => 'ما هي عاصمة المملكة العربية السعودية؟',
                'answer' => 'الرياض',
                'quiz_id' => 1,
                'options' => '["الرياض", "جدة", "مكة", "المدينة المنورة"]',
                'created_at' => '2024-08-08 00:00:00',
                'updated_at' => '2024-08-08 00:00:00'
            ],
            [
                'question' => 'هل الشمس تشرق من الشرق؟',
                'answer' => 'صحيح',
                'quiz_id' => 1,
                'options' => '["صحيح", "خطأ"]',
                'created_at' => '2024-08-08 00:00:00',
                'updated_at' => '2024-08-08 00:00:00'
            ],
            [
                'question' => 'ما هو أكبر محيط في العالم؟',
                'answer' => 'المحيط الهادئ',
                'quiz_id' => 1,
                'options' => '["المحيط الأطلسي", "المحيط الهادئ", "المحيط الهندي", "المحيط المتجمد الشمالي"]',
                'created_at' => '2024-08-08 00:00:00',
                'updated_at' => '2024-08-08 00:00:00'
            ],
            [
                'question' => 'هل القطط تعتبر من الثدييات؟',
                'answer' => 'صحيح',
                'quiz_id' => 1,
                'options' => '["صحيح", "خطأ"]',
                'created_at' => '2024-08-08 00:00:00',
                'updated_at' => '2024-08-08 00:00:00'
            ],
            [
                'question' => 'ما هي اللغة الرسمية في البرازيل؟',
                'answer' => 'البرتغالية',
                'quiz_id' => 1,
                'options' => '["الإسبانية", "البرتغالية", "الإنجليزية", "الفرنسية"]',
                'created_at' => '2024-08-08 00:00:00',
                'updated_at' => '2024-08-08 00:00:00'
            ],
        ];

        $quiz2 = Quiz::create([
            'user_id' => $teacher->id,
            'name' => 'الاختبار رقم 2',
            'subject_id' => $subject->id,
            'note' => 'الاختبار رقم 2'
        ]);

        $beforeFourDays = Carbon::now()->subDays(4);

        // Generate the date one hour after "yesterday"
        $oneHourLater = $beforeFourDays->copy()->addHour();


        Question::insert($questions);
        // Insert multiple records using the insert method
        Question::insert(array_map(function($item) {
            return [...$item, 'quiz_id' => 2];
        }, $questions));

        $quiz2->groups()->attach($group, [
            'start_time' => $beforeFourDays,
            'end_time' => $oneHourLater,
        ]);


        // Get the first day of the previous month
        $firstDayOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();

        // Get the last day of the previous month
        $lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

        // Generate 5 random dates in the previous month
        for ($i = 0; $i < 3; $i++) {
            $randomDateInPreviousMonth = Carbon::createFromTimestamp(
                mt_rand($firstDayOfPreviousMonth->timestamp, $lastDayOfPreviousMonth->timestamp)
            );

            Attendance::create([
               'status' => AttendanceStatus::Absent,
               'note' => '',
               'date' => $randomDateInPreviousMonth->format('Y-m-d'),
               'lecture_id' => $lecture->id,
               'user_id' => $student->id
            ]);
        }

    }
}
