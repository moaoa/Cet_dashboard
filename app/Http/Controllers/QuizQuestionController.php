<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class QuizQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $quizId
     * @return JsonResponse
     */
    public function index($quizId): JsonResponse
    {
        $questions = QuizQuestion::where('quiz_id', $quizId)->get();
        return response()->json(['questions' => $questions]);
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
            'quiz_id' => 'required|numeric',
            'question' => 'required|string',
            'answer' => 'required|string',
            'type' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $question = QuizQuestion::create($request->all());
        return response()->json(['question' => $question], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $question = QuizQuestion::findOrFail($id);
        return response()->json(['question' => $question]);
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
        $question = QuizQuestion::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|numeric',
            'question' => 'required|string',
            'answer' => 'required|string',
            'type' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $question->update($request->all());
        return response()->json(['question' => $question]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $question = QuizQuestion::findOrFail($id);
        $question->delete();
        return response()->json(['message' => 'Quiz question deleted']);
    }
}
