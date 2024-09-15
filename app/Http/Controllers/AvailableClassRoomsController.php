<?php

namespace App\Http\Controllers;

use App\Enums\WeekDays;
use App\Models\Lecture;
use App\Models\ClassRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AvailableClassRoomsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'day_of_week' => ['required', Rule::enum(WeekDays::class)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $startTime = Carbon::createFromFormat('H:i', $request->input('start_time'));
        $endTime = Carbon::createFromFormat('H:i', $request->input('end_time'));

        $maxTime = Carbon::now()->setHour(18)->setMinute(0);

        if ($endTime->gt($maxTime)) {
            return response()->json(['message' => 'لا يوجد محاضرات بعد الساعة 6'], 422);
        }

        $lecturesInTimeRange = Lecture::where('day_of_week', $request->input('day_of_week'))
            ->where('start_time', '<=', $request->input('start_time'))
            ->where('end_time', '>', $request->input('start_time'))
            ->orWhere(function ($query) use ($request) {
                $query->where('start_time', '>', $request->input('end_time'))
                    ->where('end_time', '<=', $request->input('end_time'));
            })
            ->get();

        $availableClassrooms = ClassRoom::whereNotIn('id', $lecturesInTimeRange->pluck('class_room_id'))->get();

        return response()->json($availableClassrooms);
    }
}
