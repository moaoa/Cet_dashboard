<?php

namespace App\Http\Controllers;

use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $userAnswers = UserAnswer::all();
        return response()->json($userAnswers);
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
            'question_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userAnswer = UserAnswer::create($request->all());
        return response()->json($userAnswer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param UserAnswer $userAnswer
     * @return JsonResponse
     */
    public function show(UserAnswer $userAnswer): JsonResponse
    {
        return response()->json($userAnswer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param UserAnswer $userAnswer
     * @return JsonResponse
     */
    public function update(Request $request, UserAnswer $userAnswer): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userAnswer->update($request->all());
        return response()->json($userAnswer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserAnswer $userAnswer
     * @return JsonResponse
     */
    public function destroy(UserAnswer $userAnswer): JsonResponse
    {
        $userAnswer->delete();
        return response()->json(null, 204);
    }
}
