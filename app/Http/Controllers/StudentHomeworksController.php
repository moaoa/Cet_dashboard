<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Comment;
use App\Models\Homework;
use App\Models\HomeworkUserAnswer;
use App\Models\Lecture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentHomeworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(String $subject_id, Request $request)
    {
        $student = $request->user();
        $groups = $student->groups()->get();

        if (!$groups) {
            return response()->json([
                'message' => 'لا يوجد مجموعات لهذا الطالب',
            ], 422);
        }

        // $items = DB::table('homework_groups')
        //     ->join('groups', 'groups.id', '=', 'homework_groups.group_id')
        //     ->join('homework', 'homework.id', '=', 'homework_groups.homework_id')
        //     ->join('homework_user_answers', 'homework_user_answers.user_id ', '=', $student->id)
        //     ->whereIn('groups.id', $groups->pluck('id'))
        //     ->where('homework.subject_id', $subject_id)
        //     ->select(
        //         'homework.id',
        //         'homework.name',
        //         'homework.attachments',
        //         'homework_user_answers.attachments as student_attachments'
        //     )
        //     ->addSelect(
        //         DB::raw("(select attachments from homework_user_answers where homework_user_answers.user_id = ' . $student->id . ' and homework_user_answers.homework_id = homework.id ) as student_attachments")
        //     )
        //     ->get();
        $items = DB::table('homework_groups')
            ->join('groups', 'groups.id', '=', 'homework_groups.group_id')
            ->join('homework', 'homework.id', '=', 'homework_groups.homework_id')
            ->join('homework_user_answers', function ($join) use ($student) {
                $join->on('homework_user_answers.homework_id', '=', 'homework.id')
                     ->where('homework_user_answers.user_id', '=', $student->id);
            })
            ->whereIn('groups.id', $groups->pluck('id'))
            ->where('homework.subject_id', $subject_id)
            ->select(
                'homework.id',
                'homework.name',
                'homework.description',
                'homework.attachments',
                'homework_user_answers.attachments as student_attachments',
                'homework_groups.due_time as date'
            )
            ->get();

        $data = $items->map(function ($item) {
            $done = $item->student_attachments !== null;

            return [
                'id' => $item->id,
                'description' => $item->description,
                'attachments' => $item->attachments ? json_decode($item->attachments): null,
                'student_attachments' => $item->attachments ? json_decode($item->student_attachments) : null,
                'done' => $done,
                'date' => $item->date
            ];
        });

        return response()->json($data);
    }
    public function addComment(String $homework_id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255', // Adjust validation rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check if the homework_id exists in the database
        $homework = Homework::find($homework_id);

        if (!$homework) {
            return response()->json(['error' => 'Homework not found'], 404);
        }

        $student = $request->user();

        Comment::create([
            'content' => $request->input('content'),
            'homework_id' => $homework_id,
            'user_id' => $student->id
        ]);

        return response()->json([], 201);
    }
}

// select homework.id, homework.name, homework.attachments from homework_groups inner join groups on groups.id = homework_groups.group_id inner join homework on homework.id = homework_groups.homework_id where groups.id in (1) and homework.subject_id = '5';
