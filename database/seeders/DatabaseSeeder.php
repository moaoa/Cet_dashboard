<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\AttendanceStatus;
use App\Enums\UserType;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\Homework;
use App\Models\Lecture;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

        $user = User::first();
        $group = Group::first();

        // Create a new Homework instance and associate it with the first instances of the related models
        $homework = Homework::create([
            'name' => 'Sample Homework',
            'url' => 'https://example.com/homework',
            'user_id' => $user->id,
            'subject_id' => $subject->id,
        ]);

        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        // Associate the Homework with the first Group
        $homework->groups()->attach($group->id, ['due_time' => $tomorrow]);


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
