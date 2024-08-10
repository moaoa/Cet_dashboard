<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersAttendingQuizController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $quizId)
    {
        try {
            $validator = Validator::make(['quizId' => $quizId], [
                'quizId' => 'required|exists:quizzes,id',
            ]);

            if ($validator->fails()) {
               return response()->json(['error' => 'Quiz not found'], 404);
            }

            $quiz = Quiz::findOrFail($quizId);

            $groups = $quiz->groups()->distinct()->get()->pluck('id');
            $students = User::query()->whereIn('group_id', $groups)->get();
            return $students;
        }  catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
