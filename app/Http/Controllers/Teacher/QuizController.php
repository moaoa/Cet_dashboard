<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
    public function getStudentResult(String $quiz_id, String $student_id): JsonResponse
    {
        $quiz = Quiz::findOrFail($quiz_id);

        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student_id)
            ->where('questions.quiz_id', $quiz->id)
            ->select('questions.id as id', 'questions.question', 'questions.answer as model_answer', 'options', 'user_answers.answer as user_answer')
            ->get();

        $data = $quiz->questions()->get()->map(function ($question) use ($user_answers) {
            return [
                'question' => $question->question,
                'model_answer' => $question->answer,
                'user_answer' => $user_answers->where('id', $question->id)->first()?->user_answer,
                'options' => $question->options
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse
    {
        $teacher = $request->user();

        $rules = [
            'group_ids'   => 'required|array',
            'group_ids.*' => 'exists:groups,id',
            'name'        => 'required|string|max:255',
            'note'        => 'nullable|string',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'subject_id'  => 'required|exists:subjects,id',

            'questions'            => 'required|array',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.options'  => 'required|array|min:2',
            'questions.*.options.*'=> 'required|string',
            'questions.*.answer_index'   => 'required|integer|min:0|max:3',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $quiz = Quiz::create([
            'name'       => $request->input('name'),
            'note'       => $request->input('note'),
            'subject_id' => $request->input('subject_id'),
            'teacher_id' => $teacher->id,
        ]);

        // Attach the quiz to the groups via pivot table
        $quiz->groups()->attach($request->input('group_ids'), [
            'start_time' => $request->input('start_time'),
            'end_time'   => $request->input('end_time')
        ]);


        $questionData = [];

        foreach ($request->input('questions') as $question) {
            $correctAnswerIndex = $question['answer_index'];
            $questionData[] = [
                'question' => $question['question'],
                'options' => json_encode($question['options']),
                'answer' => $question['options'][$correctAnswerIndex],
                'quiz_id' => $quiz->id,
            ];
        }

        Question::insert($questionData);


        // Return a success response with the quiz details
        return response()->json([
            'message' => 'Quiz created successfully',
            'quiz'    => $quiz,
        ], 201);
    }
}
