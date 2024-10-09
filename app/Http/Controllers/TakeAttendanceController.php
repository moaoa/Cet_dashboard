<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Mail\AttendanceNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Lecture;
use App\Models\User;
use App\Models\Attendance;
use App\Services\OneSignalNotifier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

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

        $validator = Validator::make($request->all(), [
            'attendance' => 'required|array',
            'attendance.*.user_id' => 'required|exists:users,id',
            'attendance.*.status' => ['required', Rule::enum(AttendanceStatus::class)],
            'attendance.*.note' => 'nullable|string|max:255',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
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
                'date' => $request->input('date'),
            ]);

            $attendance->save();
        }

        $absentUsers = array_filter(
            $request->input('attendance'),
            function ($item) {
                return $item['status'] == AttendanceStatus::Absent->value;
            }
        );

        $userIds = array_map(function ($item) {
            return $item['user_id'];
        }, $absentUsers);

        $users = User::whereIn('id', $userIds)->get();

        OneSignalNotifier::init();

        foreach ($users as $user) {
            $message = 'تم تسجيلك غياب في المحاضرة للمادة ' . $lecture->subject->name . " راجع نسبة حضورك";

            $userSubscriptions = json_decode($user->device_subscriptions, true);

            OneSignalNotifier::sendNotificationToUsers($userSubscriptions, $message);

            Mail::to($user->email)->send(new AttendanceNotification($message));
        }

        return response()->json(['message' => 'تم تسجيل الحضور'], 201);
    }
}
