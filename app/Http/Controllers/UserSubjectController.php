<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserSubjectController extends Controller
{
    public function index(): JsonResponse
    {
        $student = User::query()->where('name', 'ahmad')->first();

        $group = $student->groups()->first();


       $lectures = Lecture::with('user', 'group', 'subject', )->where('group_id', $group->id)->get();

       $data =  $lectures->map(function($lecture) use ($group){
            return [
                'id' => $lecture->subject->id,
                'name' => $lecture->subject->name,
                'teacher_name' => $lecture->user->name,
                'group_name' => $group->name,
            ];
        });

        return response()->json($data);
    }
}
