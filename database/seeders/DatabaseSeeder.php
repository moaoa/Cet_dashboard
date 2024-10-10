<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\AttendanceStatus;

use App\Models\Admin;
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
        Admin::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
        ]);
        $this->call([
            ClassroomsSeeder::class,
            studentSeeders::class,
            TeachersSeeders::class,
            SemestersSeeders::class,
            SubjectsSeeder::class,
            SubjectTeacherSeeder::class,
            // GroupsSeeder::class,
            // GroupsStudentsSeeder::class,
            // GroupsSubjectsSeeder::class,
            StudentsSubjectsSeeder::class
        ]);
    }
}
