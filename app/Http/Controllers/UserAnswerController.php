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
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $userAnswer = UserAnswer::query()->findOrFail($id);
        return response()->json($userAnswer);
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
            'question_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userAnswer = UserAnswer::query()->findOrFail($id);
        $userAnswer->update($request->all());
        return response()->json($userAnswer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $userAnswer = UserAnswer::query()->findOrFail($id);
        $userAnswer->delete();
        return response()->json(null, 204);
    }
}
