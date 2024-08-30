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
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'moaad',
            'ref_number' => 1111,
            'type' => 1,
            'password' => Hash::make('password'),
            'email' => 'moaadbn3@gmail.com',
            'email_verified_at' => now(),
            'phone_number' => '1222223',
        ]);
      // Seed Semesters
        Semester::factory()->count(3)->create();

        // Seed Subjects
        Subject::factory()->count(10)->create();

        // Seed ClassRooms
        ClassRoom::factory()->count(5)->create();

        Group::factory()->count(2)->create();

        Homework::factory()->count(2)->create();

        // Seed Groups
        $group = Group::first();
        $classRoom = ClassRoom::first();
        $subject = Subject::first();
        $semester = Semester::first();

        // Create a new Lecture instance and associate it with the first instances of the related models
        $lecture = Lecture::create([
            'start_time' => '2023-08-01 10:00:00',
            'end_time' => '2023-08-01 12:00:00',
            'day_of_week' => 1,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'user_id' => 1, // Assuming there's a user with ID 1
        ]);

        Lecture::create([
            'start_time' => '2023-08-01 12:00:00',
            'end_time' => '2023-08-01 15:00:00',
            'day_of_week' => 2,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'user_id' => 1, // Assuming there's a user with ID 1
        ]);

        Lecture::create([
            'start_time' => '2023-08-01 15:00:00',
            'end_time' => '2023-08-01 18:00:00',
            'day_of_week' => 2,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'user_id' => 1, // Assuming there's a user with ID 1
        ]);

        Lecture::create([
            'start_time' => '2023-08-01 5:00:00',
            'end_time' => '2023-08-01 14:00:00',
            'day_of_week' => 3,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'group_id' => $group->id,
            'user_id' => 1, // Assuming there's a user with ID 1
        ]);

        $user = User::first();
        $group = Group::first();

        // Create a new Homework instance and associate it with the first instances of the related models
        $homework = Homework::create([
            'name' => 'Sample Homework',
            'description' => 'some description',
            'attachments' => json_encode([
                'name' => 'Homework II.png',
                'url' => 'https://www.halpernadvisors.com/wp-content/uploads/2022/09/HW.jpg'
            ]),
            'user_id' => $user->id,
            'subject_id' => $subject->id,
        ]);

        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        // Associate the Homework with the first Group
        $homework->groups()->attach($group->id, ['due_time' => $tomorrow]);

        $pivot = $homework->groups()->where('group_id', $group->id)->first()->pivot;


        $student = User::create([
            'name' => 'ahmad',
            'ref_number' => 2222,
            'type' => UserType::Student,
            'password' => Hash::make('password'),
            'email' => 'ahmad@gmail.com',
            'email_verified_at' => now(),
            'phone_number' => '1222223',
            'group_id' => $group->id
        ]);
        // Now create the comment
        $comments = Comment::create([
            'content' => '>_<',
            'user_id' => $student->id,
            'homework_id' => $homework->id
        ]);

        $quiz = Quiz::create([
            'user_id' => $user->id,
            'subject_id' => $subject->id,
            'note' => 'التسليم يوم 30 تسعة >_<تن'
        ]);

        $quiz->groups()->attach($group, [
            'start_time' => now(),
            'end_time' => '2024-9-30',
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

        // Insert multiple records using the insert method
        Question::insert($questions);



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
