<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentLecturesController extends Controller
{
    public function index(): JsonResponse
    {
        // TODO: fix groups to group
        $group = User::query()->find(1)->groups()->first();
        $lectures = Lecture::query()->with('subject')->where('group_id', $group->id)->get();
        return response()->json($lectures);
    }
}
