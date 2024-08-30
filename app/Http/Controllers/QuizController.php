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
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|min:1|exists:questions,id',
            'answers.*.answer' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $student = User::query()->where('name', 'ahmad')->first();

        $quiz = Quiz::findOrFail($quiz_id);


        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student->id)
            ->where('questions.quiz_id', $quiz->id)
            ->get();

        $done = $user_answers->contains(function ($item) use ($student) {
             return $item->user_id == $student->id;
        });

        if($done){
           return response()->json(['message'=> 'عذرا تم اجتياز هذا الاختبار من قبل'], 422);
        }

        $answers =  array_map(function($item) use ($student) {
            return [...$item, 'user_id' => $student->id];
        }, $request->input('answers'));

        UserAnswer::insert($answers);
        return response()->json(['message' => 'done'], 201);
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
    public function studentQuizzes(): JsonResponse
    {
        $student = User::query()->where('name', 'ahmad')->first();

        $group = $student->groups()->first();

        $pivot = DB::table('quiz_groups')
            ->join('quizzes', 'quizzes.id', '=', 'quiz_groups.quiz_id')
            ->join('groups', 'groups.id', '=', 'quiz_groups.group_id')
            ->select('*')
            ->get();

        $quizzes = $student->groups()->first()->quizzes()->get();

        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student->id)
            ->whereIn('questions.quiz_id', $quizzes->pluck('id'))->get();

        $done = $user_answers->contains(function ($item) use ($student) {
             return $item->user_id == $student->id;
        });

        $data =  $quizzes->map(function ($item) use ($done, $group, $pivot) {
            $pivot_record = $pivot->where('quiz_id', $item->id)->where('group_id', $group->id)->first();
             return [
                'id' => $item->id,
                'name' => $item->name,
                'note' => $item->note,
                'subject_name' => $item->subject->name,
                'start_time' => $pivot_record->start_time,
                'end_time' => $pivot_record->end_time,
                'done' => $done,
                'questions' => QuestionResource::collection($item->questions),
            ];
        });
        return response()->json($data);
    }
}
