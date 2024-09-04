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
    public function index(String $subject_id, Request $request): JsonResponse
    {
        $student = $request->user();
        // TODO: fix groups to group
        $group = $student->groups()->first();

        if (!$group) {
            return response()->json([
                'message' => 'لا يوجد مجموعات لهذا الطالب',
            ], 422);
        }

        $homeworks = Homework::query()
            ->with([
                'comments' => function ($query) use ($group) {
                    $query->whereIn('user_id', $group->users()->get('id'));
                },
                'groups' => function ($query) use ($group) {
                    $query->where('groups.id', $group->id)
                        ->withPivot('due_time');
                }
            ])->where('subject_id', $subject_id)->get();

        $group_users = $group->users()->get();

        $answers = HomeworkUserAnswer::where('user_id', $student->id)
            ->whereIn('homework_id', $homeworks->pluck('id'))
            ->get();

        $data = $homeworks->map(function ($homework) use ($group, $group_users, $answers) {
            $done = $answers->where('homework', $homework->id)->count() > 0;
            $comments = $homework->comments->map(function ($comment) use ($group_users) {
                return [
                    'content' => $comment->content,
                    'user_name' => $group_users->firstWhere('id', $comment->user_id)->name,
                    'created_at' => $comment->created_at
                ];
            });
            return [
                'id' => $homework->id,
                'name' => $homework->name,
                'description' => $homework->description,
                'attachments' => json_decode($homework->attachments),
                'comments' => $comments,
                'date' => $pivotTable = DB::table('homework_groups')
                    ->select('due_time')
                    ->where('group_id', $group->id)
                    ->where('homework_id', $homework->id)
                    ->first()?->due_time,
                'done' => $done
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
