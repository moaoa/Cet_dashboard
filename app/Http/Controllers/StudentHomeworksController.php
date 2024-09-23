<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Homework;
use App\Models\HomeworkUserAnswer;
use App\Models\Lecture;
use App\Models\User;
use App\Services\OneSignalNotifier;
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

        $items = DB::table('homework_groups')
            ->join('groups', 'groups.id', '=', 'homework_groups.group_id')
            ->join('homework', 'homework.id', '=', 'homework_groups.homework_id')
            ->leftJoin('homework_user_answers', function ($join) use ($student) {
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

        $comments = Comment::with('commentable')->whereIn('homework_id', $items->pluck('id'))->get();

        $data = $items->map(function ($item) use ($comments) {
            $done = $item->student_attachments !== null;

            $homeworkComments = $comments->where('homework_id', $item->id)->map(function ($comment) {
                return [
                    'name' => $comment->commentable->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    'image' => $comment->commentable_type == User::class ? 'https://st2.depositphotos.com/3369547/11438/v/380/depositphotos_114380960-stock-illustration-graduation-cap-and-boy-icon.jpg' : 'https://st2.depositphotos.com/3557671/11164/v/950/depositphotos_111644880-stock-illustration-man-avatar-icon-of-vector.jpg'
                ];
            })->values()->toArray();

            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'attachments' => $item->attachments ? json_decode($item->attachments) : null,
                'student_attachments' => $item->attachments ? json_decode($item->student_attachments) : null,
                'comments' => $homeworkComments,
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
            'commentable_id' => $student->id,
            'commentable_type' => User::class
        ]);



        OneSignalNotifier::init();
        $message = ' تم إضافة تعليق جديد للواجب ' . $homework->name;

        $group = DB::table('homework_groups')
            ->join('homework', 'homework.id', '=', 'homework_groups.homework_id')
            ->where('homework_groups.homework_id', $homework_id)
            ->whereIn('homework_groups.group_id', $student->groups()->pluck('groups.id'))
            ->select('homework_groups.group_id')
            ->first();

        $group = Group::with('users', 'teacher')->find($group->group_id);

        if ($group) {
            $subscriptions = $group->users()->get()->map(function ($user) {
                return $user->device_subscriptions();
            });
            $subscriptions = $subscriptions->flatten()->unique();
            $subscriptions = array_merge($subscriptions, $group->teacher->device_subscriptions);
            OneSignalNotifier::sendNotificationToUsers($subscriptions, $message);
        }

        return response()->json([], 201);
    }
}

// select homework.id, homework.name, homework.attachments from homework_groups inner join groups on groups.id = homework_groups.group_id inner join homework on homework.id = homework_groups.homework_id where groups.id in (1) and homework.subject_id = '5';
