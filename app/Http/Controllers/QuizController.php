<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Models\Group;
use App\Models\Question;
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

        $groups = $student->groups()->get();

        $quiz = Quiz::findOrFail($quiz_id);

        $end_time = DB::table('quiz_groups')
            ->whereIn('group_id', $groups->pluck('id'))
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

        $groups = $student->groups()->get();

        $items = DB::table('quizzes')
            ->join('quiz_groups', 'quiz_groups.quiz_id', '=', 'quizzes.id')
            ->join('groups', 'groups.id', '=', 'quiz_groups.group_id')
            ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
            ->whereIn('groups.id', $groups->pluck('id'))
            ->select(
                'quizzes.id',
                'quizzes.name',
                'quizzes.note',
                'subjects.name as subject_name',
                'quiz_groups.start_time',
                'quiz_groups.end_time',
                'groups.id as group_id',
            )
            ->get();

        $questions = Question::whereIn('quiz_id', $items->pluck('id'))
            ->get();

        $user_answers = DB::table('user_answers')
            ->join('questions', 'questions.id', '=', 'user_answers.question_id')
            ->where('user_answers.user_id', $student->id)
            ->whereIn('questions.quiz_id', $items->pluck('id'))->get();

        $data = $items->map(function ($quiz) use ($user_answers, $questions) {
            $done = $user_answers->contains(function ($answer) use ($quiz) {
                return $answer->quiz_id == $quiz->id;
            });

            $quizQuestions = $questions
                ->where('quiz_id', $quiz->id);

            return [
                'id' => $quiz->id,
                'name' => $quiz->name,
                'note' => $quiz->note,
                'subject_name' => $quiz->subject_name,
                'start_time' => $quiz->start_time,
                'end_time' => $quiz->end_time,
                'done' => $done,
                'questions' => QuestionResource::collection($quizQuestions),
            ];
        });
        return response()->json($data);
    }
}
