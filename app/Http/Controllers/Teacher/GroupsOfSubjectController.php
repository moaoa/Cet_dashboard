<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupsOfSubjectController extends Controller
{
    public function index(Request $request, String $subject_id)
    {
        $teacher = $request->user();
        $groups = Group::where('teacher_id', $teacher->id)->get();

        return response()->json($groups);
    }
}
