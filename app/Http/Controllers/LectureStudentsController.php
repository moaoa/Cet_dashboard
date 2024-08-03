<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\User;
use Illuminate\Http\Request;

class LectureStudentsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $lecture_id)
    {
        $lecture = Lecture::query()->where('id', $lecture_id)->first();

        $students = User::query()->where('group_id', $lecture->group_id)->get();

        return response()->json($students);
    }
}
