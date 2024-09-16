<?php

namespace App\Http\Controllers\Teacher;

use App\Actions\UserQuizScoreAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\QuizResource;
use App\Models\Group;
use App\Models\Quiz;
use App\Models\QuizScore;
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
            ->select('quizzes.id', 'quizzes.name', 'subjects.name as subject_name', 'start_time', 'end_time', 'note', 'groups.name as group_name', 'groups.id as group_id')
            ->get();

        return response()->json($items);
    }

    // public function quizResults(Request $request, String $quiz_id): JsonResponse
    public function quizResults(Request $request, String $quiz_id)
    {
        $teacher = $request->user();

        $isTeacherOwnQuiz =  $teacher->quizzes()->pluck('id')->contains(function($id) use ($quiz_id){
            return $id == $quiz_id;
        });

        if(!$isTeacherOwnQuiz) {
            return response()->json(['message' => 'لا يوجد لديك اختبار بهذا المعرف'], 422);
        }

        $quiz = Quiz::with('groups.users', 'teacher', 'subject')->find($quiz_id);
        $quizGroupsIds = $quiz->groups->pluck('id');
        $quizTotalScore = $quiz->questions()->count();

        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->join('group_user', 'group_user.user_id', '=', 'user_answers.user_id')
            ->join('groups', 'groups.id', '=', 'group_user.group_id')
            ->join('users', 'users.id', '=', 'group_user.user_id')
            ->whereIn('groups.id', $quizGroupsIds)
            ->where('questions.quiz_id', $quiz_id)
            ->select(
                'user_answers.user_id',
                'users.ref_number',
                'groups.name as group_name',
                'users.name as user_name',
                DB::raw('SUM(CASE WHEN user_answers.answer = questions.answer THEN 1 ELSE 0 END) as correct_answers_count')
            )
            ->groupBy('user_answers.user_id', 'groups.id', 'users.id')
            ->get();

        return response()->json([
            'quiz_name' => $quiz->name,
            'subject_name' => $quiz->subject->name,
            'teacher_name' => $quiz->teacher->name,
            'answers' => $user_answers,
            'total' => $quizTotalScore
        ]);
    }
}
