<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Http\Request;

class LectureStudentsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, String $lecture_id)
    {
        $group = Lecture::find($lecture_id)->group()->first();

        $students = $group->users()->get();

        return response()->json(UserResource::collection($students));
    }
}
