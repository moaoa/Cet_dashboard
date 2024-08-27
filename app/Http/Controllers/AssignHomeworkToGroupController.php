<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Homework;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignHomeworkToGroupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|numeric',
            'homework_id' => 'required|numeric',
            'due_time' => 'nullable|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $group = Group::findOrFail($request->input('group_id'));
        $homework = Homework::findOrFail($request->input('homework_id'));

        $homework->groups()->attach($group, [
            'due_time' => $request->input('due_time'),
        ]);

        return response()->json([], 201);
    }
}
