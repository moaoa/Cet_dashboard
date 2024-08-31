<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSubjectController extends Controller
{
    public function index(): JsonResponse
    {
        $student = User::query()->where('name', 'ahmad')->first();

        $subjects = UserSubject::with('subject')->where('user_id', $student->id)->where('passed', false)->get();
        $data = $subjects->map(function ($subject){
            return $subject->subject;
        });

        return response()->json($data);
    }
}
