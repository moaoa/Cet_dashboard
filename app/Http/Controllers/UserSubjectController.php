<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubjectResource;
use App\Models\UserSubject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserSubjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();

        $group = $student->groups()->first();

        $subjects = UserSubject::with('group', 'user', 'subject')->where('user_id', $student->id)->get();

        return response()->json(SubjectResource::collection($subjects));
    }
}
