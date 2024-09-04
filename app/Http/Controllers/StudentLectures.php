<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Lecture;
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
        // TODO: fix groups to group
        $group = $student->groups()->first();

        if(!$group){
            return response()->json([
                'message' => 'لا يوجد مجموعات لهذا الطالب',
            ], 422);
        }

        $studentAbsentDaysCount = Lecture::query()->with(['attendances' => function($query) use($student) {
            $query
                ->where('status', AttendanceStatus::Absent)
                ->where('user_id', $student->id);
        }])->count();

        $teacherAbsentDaysCount = Lecture::query()->with(['attendances' => function($query) use($student) {
            $query
                ->where('status', AttendanceStatus::Absent)
                ->where('user_id', 'lecture.user_id');
        }])->count();

        $absencePercentage = ($studentAbsentDaysCount / (16 - $teacherAbsentDaysCount));

        $lectures = Lecture::query()->with(['subject'])->where('group_id', $group->id)->get();

        $data = $lectures->map(function ($lecture) use ($absencePercentage) {
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
