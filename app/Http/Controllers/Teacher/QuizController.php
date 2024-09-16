<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teacher = $request->user();
        $quizzes = Quiz::with('subject', 'groups')->where('teacher_id', $teacher->id)->get();

        $items = DB::table('quiz_groups')
            ->join('quizzes', 'quizzes.id', '=', 'quiz_groups.quiz_id')
            ->join('groups', 'groups.id', '=', 'quiz_groups.group_id')
            ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
            ->where('groups.teacher_id', $teacher->id)
            ->select('quizzes.id', 'quizzes.name', 'subjects.name as subject_name', 'start_time', 'end_time', 'note')
            ->get();

        return response()->json($items);
    }
}
