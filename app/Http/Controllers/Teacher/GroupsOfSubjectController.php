<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsOfSubjectController extends Controller
{
    public function index(Request $request, String $subject_id)
    {
        $teacher = $request->user();

        $groups = DB::table('teacher_groups')
            ->join('groups', 'groups.id', '=', 'teacher_groups.group_id')
            ->join('subject_teacher', 'subject_teacher.teacher_id', '=', 'teacher_groups.teacher_id')
            ->where('subject_teacher.subject_id', $subject_id)
            ->where('teacher_groups.teacher_id', $teacher->id)
            ->select('groups.id', 'groups.name')
            ->get();

        return response()->json($groups);
    }
}
