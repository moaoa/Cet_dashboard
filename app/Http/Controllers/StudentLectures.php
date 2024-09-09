<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Lecture;
use App\Models\TeacherAbsence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentLectures extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();
        $groups = $student->groups()->get();

        if(!$groups){
            return response()->json([
                'message' => 'لا يوجد مجموعات لهذا الطالب',
            ], 422);
        }

        $studentAbsentDays = Attendance::where('user_id', $student->id)->get();

        $teacherAbsentDays = TeacherAbsence::all();

        $lectures = Lecture::query()
            ->with(['subject'])
            ->whereIn('group_id', $groups->pluck('id'))
            ->get();

        $data = $lectures->map(function ($lecture) use ($teacherAbsentDays, $studentAbsentDays) {

            $teacherAbsentDaysInLectureCount = $teacherAbsentDays
                ->where('lecture_id', $lecture->id)
                ->where('teacher_id', $lecture->teacher_id)
                ->count();

            $studentAbsentDaysInLectureCount = $studentAbsentDays
                ->where('lecture_id', $lecture->id)
                ->count();

            $absencePercentage = ($studentAbsentDaysInLectureCount / (16 - $teacherAbsentDaysInLectureCount));

            return [
                'id' => $lecture->id,
                'start_time' => Carbon::parse($lecture->start_time)->format('H:i'),
                'end_time' => Carbon::parse($lecture->end_time)->format('H:i'),
                'day_of_week' => $lecture->day_of_week,
                'subject_name' => $lecture->subject->name,
                'absence_percentage' => $absencePercentage
            ];
        });

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Lecture $lecture
     * @return JsonResponse
     */
    public function show(string $user_id): JsonResponse
    {
        $lecture = Lecture::query()->where('user_id', $user_id)->first();
        return response()->json($lecture);
    }
}
