<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $lectures = Lecture::query()->with('subject')->get();
        return response()->json($lectures);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'day_of_week' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'class_room_id' => 'required|numeric',
            'group_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        $lecture = Lecture::create($request->all());
        return response()->json($lecture, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Lecture $lecture
     * @return JsonResponse
     */
    public function show(Lecture $lecture): JsonResponse
    {
        return response()->json($lecture);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Lecture $lecture
     * @return JsonResponse
     */
    public function update(Request $request, Lecture $lecture): JsonResponse
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'day_of_week' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'class_room_id' => 'required|numeric',
            'group_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        $lecture->update($request->all());
        return response()->json($lecture);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lecture $lecture
     * @return JsonResponse
     */
    public function destroy(Lecture $lecture): JsonResponse
    {
        $lecture->delete();
        return response()->json(null, 204);
    }
}
