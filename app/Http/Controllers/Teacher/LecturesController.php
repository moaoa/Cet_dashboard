<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\WeekDays;
use App\Http\Controllers\Controller;
use App\Models\Lecture;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Teacher\LectureResource;
use App\Mail\LectureNotification;
use App\Models\Subject;
use App\Services\OneSignalNotifier;
use Carbon\WeekDay;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class LecturesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teacher = $request->user();
        $lectures = Lecture::with('group.users', 'subject')->where('teacher_id', $teacher->id)->get();

        return response()->json(LectureResource::collection($lectures));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => ['required', Rule::enum(WeekDays::class)],
            'duration' => 'required|integer|min:1',
            'start_time' => 'required|date_format:H:i',
            'class_room_id' => 'required|int|max:255',
            'lecture_date' => 'required_if:one_time_lecture,true|date',
            'one_time_lecture' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $startTime = Carbon::createFromFormat('H:i', $request->input('start_time'));

        // Add the duration in minutes to the start time
        $endTime = $startTime->copy()->addMinutes((int)$request->input('duration'));

        $maxTime = Carbon::now()->setHour(18)->setMinute(0);

        if ($endTime->gt($maxTime)) {
            return response()->json(['message' => 'لا يوجد محاضرات بعد الساعة 6'], 422);
        }

        $lecture = new Lecture();
        $lecture->group_id = $request->input('group_id');
        $lecture->teacher_id = $request->user()->id;
        $lecture->subject_id = $request->input('subject_id');
        $lecture->start_time = $startTime;
        $lecture->end_time = $endTime;
        $lecture->day_of_week = $request->input('day_of_week');
        $lecture->class_room_id = $request->input('class_room_id');

        if ($request->input('one_time_lecture')) {
            $lecture->deleted_at = Carbon::parse($request->input('lecture_date'))->setHour(23)->setMinute(0);
        }

        $lecture->save();

        $subject = Subject::find($request->input('subject_id'));
        $weekDay = WeekDays::from($request->input('day_of_week'));
        $lectureType = $request->input('one_time_lecture') ? "تعويضية" : "جديدة";

        $message = "تم إضافة محاضرة {$lectureType} يوم {$weekDay->toArabic()} لمادة {$subject->name}";

        // Fetch users for the group
        $users = $lecture->group->users;

        OneSignalNotifier::init();

        foreach ($users as $user) {
            // Send OneSignal notification
            // OneSignalNotifier::sendNotificationToUsers(
            //     json_decode($user->device_subscriptions) ?? [],
            //     $message,
            //     $url = "https://cet-management.moaad.ly"
            // );
            Mail::to($user->email)->send(new LectureNotification($message));
        }

        return response()->json(['message' => 'تمت إضافة المحاضرة بنجاح']);
    }
}
