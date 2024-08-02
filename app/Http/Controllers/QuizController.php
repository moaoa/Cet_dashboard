<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
        $request->validate([
            'user_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
        ]);

        $quiz = Quiz::create($request->all());
        return response()->json($quiz, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function show(Quiz $quiz): JsonResponse
    {
        return response()->json($quiz);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function update(Request $request, Quiz $quiz): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
        ]);

        $quiz->update($request->all());
        return response()->json($quiz);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function destroy(Quiz $quiz): JsonResponse
    {
        $quiz->delete();
        return response()->json(null, 204);
    }
}
