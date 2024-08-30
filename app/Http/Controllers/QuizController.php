<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Models\Group;
use App\Models\Quiz;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\table;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $quizzes = Quiz::all();
        return response()->json($quizzes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $quiz = Quiz::create($request->all());
        return response()->json($quiz, 201);
    }

    public function answerQuiz(Request $request, String $quiz_id): JsonResponse
    {
        $student = User::query()->where('name', 'ahmad')->first();

        $quiz = Quiz::findOrFail($quiz_id);

        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student->id)
            ->where('questions.quiz_id', $quiz->id)->get();

        $done = $user_answers->contains(function ($item) use ($student) {
             return $item->user_id == $student->id;
        });

        if($done){
           return response()->json(['message'=> 'عذرا تم اجتياز هذا الاختبار من قبل'], 422);
        }

        $validator = Validator::make($request->all(), [
            '*' => 'array',
            '*.question_id' => 'required|integer|min:1|exists:questions',
            '*.answer' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $allQuestionsOptions = [];

        $questions = $quiz->questions()->forEach(function ($question) {
            array_push(...json_encode($question->options));
        });

        $answers = $request->all();

        if(in_array($answers)){

        }

        UserAnswer::create([]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $quiz = Quiz::with('questions', 'groups')->findOrFail($id);
        return response()->json(new QuizResource($quiz));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $quiz = Quiz::findOrFail($id);
        $quiz->update($request->all());
        return response()->json($quiz);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return response()->json(null, 204);
    }
    public function studentQuizzes()
    // public function studentQuizzes(): JsonResponse
    {
        $student = User::query()->where('name', 'ahmad')->first();

        $items = DB::table('quiz_groups')
            ->join('quizzes', 'quizzes.id', '=', 'quiz_groups.quiz_id')
            ->join('groups', 'groups.id', '=', 'quiz_groups.group_id')
            ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
            ->join('questions', 'questions.quiz_id', '=', 'quizzes.id')
            ->select('subjects.name as subject_name', 'start_time', 'end_time', 'note', 'questions.options')
            ->get();

        $quizzes = $student->groups()->first()->quizzes()->get();

        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student->id)
            ->whereIn('questions.quiz_id', $quizzes->pluck('id'))->get();

        $done = $user_answers->contains(function ($item) use ($student) {
             return $item->user_id == $student->id;
        });

        $data =  $quizzes->map(function ($quiz) use ($done) {
             return [
                'id' => $quiz->id,
                'note' => $quiz->note,
                'subject_name' => $quiz->subject->name,
                'done' => $done,
                'questions' => QuestionResource::collection($quiz->questions),
            ];
        });
        return response()->json($data);
    }
}
