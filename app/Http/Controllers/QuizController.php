<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Models\Group;
use App\Models\Quiz;
use App\Models\User;
use App\Models\UserAnswer;
use Carbon\Carbon;
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

        $student = $request->user();

        $studentGroup = $student->groups()->first();

        $quiz = Quiz::findOrFail($quiz_id);

        $end_time = DB::table('quiz_groups')
            ->where('group_id', $studentGroup->id)
            ->where('quiz_id', $quiz_id)
            ->select('end_time')
            ->first()->end_time;

        if (Carbon::parse($end_time)->isPast()) {
            return response()->json([
                'message' => 'لقد إنتهى وقت الاختبار'
            ], 422);
        }
        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student->id)
            ->where('questions.quiz_id', $quiz->id)
            ->get();

        $done = $user_answers->contains(function ($item) use ($student) {
            return $item->user_id == $student->id;
        });

        if ($done) {
            return response()->json(['message' => 'عذرا تم اجتياز هذا الاختبار من قبل'], 422);
        }

        $answers =  array_map(function ($item) use ($student) {
            return [...$item, 'user_id' => $student->id];
        }, $request->input('answers'));

        UserAnswer::insert($answers);
        return response()->json(['message' => 'done'], 201);
    }
    public function quizResult(Request $request, String $quiz_id): JsonResponse
    {
        $student = $request->user();

        $quiz = Quiz::findOrFail($quiz_id);


        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student->id)
            ->where('questions.quiz_id', $quiz->id)
            ->select('questions.id as id', 'questions.question', 'questions.answer as model_answer', 'options', 'user_answers.answer as user_answer')
            ->get();

        $score = 0;

        $user_answers->each(function ($item) use (&$score) {
            if ($item->model_answer == $item->user_answer) {
                $score++;
            }
        });

        $data = $quiz->questions()->get()->map(function ($question) use ($user_answers) {
            return [
                'question' => $question->question,
                'model_answer' => $question->answer,
                'user_answer' => $user_answers->where('id', $question->id)->first()?->user_answer,
                'options' => $question->options
            ];
        });

        return response()->json(['score' => $score, 'answers' => $data]);
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
    public function studentQuizzes(Request $request): JsonResponse
    {
        $student = $request->user();

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


        $data =  $quizzes->map(function ($quiz) use ($group, $pivot, $user_answers) {
            $pivot_record = $pivot->where('quiz_id', $quiz->id)->where('group_id', $group->id)->first();

            $done = $user_answers->contains(function ($answer) use ($quiz) {
                return $answer->quiz_id == $quiz->id;
            });

            return [
                'id' => $quiz->id,
                'name' => $quiz->name,
                'note' => $quiz->note,
                'subject_name' => $quiz->subject->name,
                'start_time' => $pivot_record->start_time,
                'end_time' => $pivot_record->end_time,
                'done' => $done,
                'questions' => QuestionResource::collection($quiz->questions),
            ];
        });
        return response()->json($data);
    }
}
