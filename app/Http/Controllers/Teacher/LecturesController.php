<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use Illuminate\Http\Request;
use App\Http\Resources\Teacher\LectureResource;

class LecturesController extends Controller
{
    //
    public function index(Request $request)
    {
        $teacher = $request->user();
        $lectures = Lecture::with('group.users', 'subject')->where('teacher_id', $teacher->id)->get();

        return LectureResource::collection($lectures);
    }
}
