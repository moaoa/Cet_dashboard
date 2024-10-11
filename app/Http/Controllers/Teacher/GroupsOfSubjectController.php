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

        $groups = DB::table('group_subject')
            ->join('groups', 'groups.id', '=', 'group_subject.group_id')
            ->join('teacher_groups', 'teacher_groups.group_id', '=', 'groups.id')
            ->join('subject_teacher', 'subject_teacher.subject_id', '=', 'group_subject.subject_id')
            ->where('teacher_groups.teacher_id', $teacher->id)
            ->where('subject_teacher.subject_id', $subject_id)
            ->select('groups.id', 'groups.name')
            ->get();

        return response()->json($groups);
    }
}
