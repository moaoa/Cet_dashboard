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

        $groups = $student->groups();

        $subjects = DB::table('user_subjects')
            ->join('group_subject', 'group_subject.subject_id', '=', 'user_subjects.subject_id')
            ->join('subjects', 'subjects.id', '=', 'group_subject.subject_id')
            ->join('groups', 'groups.id', '=', 'group_subject.group_id')
            ->join('subject_teacher', 'subject_teacher.subject_id', '=', 'subjects.id')
            ->join('teachers', 'teachers.id', '=', 'subject_teacher.teacher_id')

            ->where('user_subjects.user_id', $student->id)
            ->select('subjects.id', 'subjects.name', 'groups.name as group_name', 'teachers.name as teacher_name')
            ->get();

        return response()->json($subjects);
    }
}
