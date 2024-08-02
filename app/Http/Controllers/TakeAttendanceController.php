<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Lecture;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;

class TakeAttendanceController extends Controller
{
    /**
     * Take attendance for a specific lecture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lectureId
     * @return JsonResponse
     */
    public function __invoke(Request $request, $lectureId): JsonResponse
    {
        $lecture = Lecture::query()->findOrFail($lectureId);

       $validator = Validator::make($request->input('attendance'), [
            '*.user_id' => 'required|exists:users,id',
            '*.status' => 'required|integer|in:1,2,3',
            '*.note' => 'nullable|string|max:255',
            '*.date' => 'required|date'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }


        // Store the attendance data
        foreach ($request->input('attendance') as $attendanceItem) {
            $attendance = new Attendance([
                'lecture_id' => $lecture->id,
                'user_id' => $attendanceItem['user_id'],
                'status' => $attendanceItem['status'],
                'note' => $attendanceItem['note'] ?? '',
                'date' => now()->format('Y-m-d'),
            ]);
            $attendance->save();
        }
        return response()->json('ok', 201);
    }
}
