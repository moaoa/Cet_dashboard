<?php

namespace App\Http\Controllers;

//use App\Http\Resources\SubjectResource;
use App\Models\Lecture;
use App\Models\UserSubject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserSubjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();

        $subjects = DB::table('user_subjects')
            ->join('group_user', function ($join) use ($student) {
                $join->on('group_user.user_id', '=', 'user_subjects.user_id')
                    ->where('group_user.user_id', '=', $student->id);
            })
            ->join('teacher_groups', function ($join) use ($student) {
                $join->on('teacher_groups.group_id', '=', 'group_user.group_id')
                    ->where('group_user.user_id', '=', $student->id);
            })
            ->join('groups', 'groups.id', '=', 'group_user.group_id')
            ->join('teachers', 'teachers.id', '=', 'teacher_groups.teacher_id')
            ->join('subjects', 'subjects.id', '=', 'user_subjects.subject_id')
            ->where('user_subjects.user_id', $student->id)
            ->select('subjects.id', 'subjects.name', 'groups.name as group_name', 'teachers.name as teacher_name')
            ->get();

        return response()->json($subjects);
    }
}
