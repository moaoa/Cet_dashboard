<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AssignQuizToGroupController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|numeric',
            'quiz_id' => 'required|numeric',
            'start_time' => 'required|date|after_or_equal:today',
            'end_time' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $group = Group::findOrFail($request->input('group_id'));
        $quiz = Quiz::findOrFail($request->input('quiz_id'));

        $quiz->groups()->attach($group, [
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ]);

        return response()->json([], 201);
    }
}
