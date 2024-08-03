<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserLectures extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(string $user_id): JsonResponse
    {
        $lectures = Lecture::query()->where('user_id', $user_id)->get();
        return response()->json($lectures);
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
