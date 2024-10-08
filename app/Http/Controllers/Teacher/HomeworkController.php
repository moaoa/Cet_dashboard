<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Homework;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\HomeworkUserAnswer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\ReminderJob;
use App\Mail\HomeworkNotification;
use App\Mail\HomeworkReminderNotification;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Services\OneSignalNotifier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $teacher = $request->user();

        $items = DB::table('subjects')
            ->join('group_subject', 'group_subject.subject_id', '=', 'subjects.id')
            ->join('groups', 'groups.id', '=', 'group_subject.group_id')
            ->where('groups.teacher_id', $teacher->id)
            ->select('groups.id as group_id', 'subjects.id as subject_id', 'subjects.name', 'groups.name as group_name')
            ->get();


        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Create a Validator instance
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'attachments' => 'nullable',
            'due_time' => 'nullable|date',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'subject_id' => 'required|exists:subjects,id',
            'group_ids' => 'required|array',
            'group_ids.*' => 'exists:groups,id',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the validated data
        $validated = $validator->validated();

        $teacher = $request->user();

        $attachments = [];

        $files = $request->file('attachments') ?? [];

        foreach ($files as $file) {
            // Get the original file name
            $fileName = $file->getClientOriginalName();
            $fileName = str_replace(' ', '_', $fileName);

            // Define a path to store the file
            $destinationPath = '/uploads/files'; // You can change this path

            // Store the file
            $path = $file->storeAs($destinationPath, $teacher->id . '-' . $fileName, 'public');

            // Add the path to the array of uploaded files

            $attachments[] = ['name' => $fileName, 'url' => asset(Storage::url($path))];
        }

        // Create the homework
        $homework = Homework::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'attachments' => json_encode($attachments),
            'teacher_id' => $teacher->id,
            'subject_id' => $validated['subject_id'],
        ]);

        // Attach the homework to the selected group (one-to-many relationship)
        $homework->groups()->attach($validated['group_ids'], ['due_time' => $request->input('due_time')]);

        $groups = Group::with('users')->whereIn('id', $request->input('group_ids'))->get();
        $subject = Subject::find($request->input('subject_id'));

        $message = ' تم إضافة واجب جديد لمادة' . $subject->name;

        OneSignalNotifier::init();


        $users = $groups->flatMap(function ($group) {
            return $group->users;
        })->unique();

        foreach ($users as $user) {
            OneSignalNotifier::sendNotificationToUsers(
                json_decode($user->device_subscriptions) ?? [],
                $message,
                $url = "https://cet-management.moaad.ly"
            );

            Mail::to($user->email)->send(new HomeworkNotification($message));
        }

        $due_time = $request->input('due_time');

        $formatted_due_time = null;

        if ($due_time && Carbon::parse($due_time)->gt(Carbon::now()->addHours(1))) {
            $formatted_due_time = Carbon::parse($due_time)->format('H:i');
        }


        if ($formatted_due_time) {
            $execution_time = Carbon::parse($formatted_due_time)->subHours(1);

            $homework_reminder_message = ' وقت تسليم الواجب اليوم الساعة: ' . $formatted_due_time;

            $mail = new HomeworkReminderNotification($homework_reminder_message);

            ReminderJob::dispatch($homework_reminder_message, $users, $mail)->delay($execution_time);
        }
        // Return a success response
        return response()->json([
            'message' => 'Homework created and assigned to group successfully',
            'homework' => $homework,
        ], 201);
    }
    /**
     * Get homeworks for a specific group and subject with their comments.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getHomeworkForGroupAndSubject(Request $request): JsonResponse
    {
        $groupId = $request->route('group');
        $subjectId = $request->route('subject');

        // Validate the route parameters
        $validator = Validator::make([
            'group_id' => $groupId,
            'subject_id' => $subjectId,
        ], [
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // The validated data
        $validated = $validator->validated();

        $groupId = $validated['group_id'];
        $subjectId = $validated['subject_id'];

        $items = Homework::where('subject_id', $subjectId)
            ->whereHas('groups', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            })
            ->with([
                'comments.commentable' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                // 'studentAttachments'
            ])
            ->get();

        $homeworks = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'date' => $item->created_at->format('Y-m-d'),  // Assuming 'created_at' is the date
                'comments' => $item->comments->map(function ($comment) {
                    return [
                        'name' => optional($comment->commentable)->name,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                        'image' => $comment->commentable_type == \App\Models\User::class
                            ? 'https://st2.depositphotos.com/3369547/11438/v/380/depositphotos_114380960-stock-illustration-graduation-cap-and-boy-icon.jpg'
                            : 'https://st2.depositphotos.com/3557671/11164/v/950/depositphotos_111644880-stock-illustration-man-avatar-icon-of-vector.jpg'
                    ];
                })->values()->toArray(),
                'attachments' => json_decode($item->attachments),
                // 'student_attachments' => $item->studentAttachments ? json_decode($item->studentAttachments) : null,
            ];
        });


        return response()->json($homeworks);
    }
    /**
     * Show the specified homework with its attachments and student attachments.
     *
     * @param Homework $homework
     * @return JsonResponse
     */
    public function show(Homework $homework): JsonResponse
    {
        $userAnswers = HomeworkUserAnswer::where('homework_id', $homework->id)
            ->with('user')
            ->get();

        $homework = $homework->toArray();
        $homework['attachments'] = json_decode($homework['attachments']);
        $homework['user_answers'] = $userAnswers->map(function ($answer) {
            return [
                'student_name' => $answer->user->name,
                'ref' => $answer->user->ref_number,
                'attachments' => json_decode($answer->attachments),
                'created_at' => $answer->created_at,
            ];
        });

        return response()->json([
            'homework' => $homework,
        ]);
    }
    public function addComment(Request $request, String $homework_id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $homework = Homework::find($homework_id);

        if (!$homework) {
            return response()->json(['error' => 'Homework not found'], 404);
        }

        $teacher = $request->user();

        Comment::create([
            'content' => $request->input('content'),
            'homework_id' => $homework_id,
            'commentable_id' => $teacher->id,
            'commentable_type' => Teacher::class
        ]);

        OneSignalNotifier::init();
        $message = ' تم إضافة تعليق جديد للواجب ' . $homework->name;

        $groupItem = DB::table('homework_groups')
            ->join('homework', 'homework.id', '=', 'homework_groups.homework_id')
            ->join('groups', 'groups.id', '=', 'homework_groups.group_id')
            ->where('homework_groups.homework_id', (int) $homework_id)
            ->where('groups.teacher_id', $teacher->id)
            ->select('homework_groups.group_id')
            ->first();

        if (!$groupItem)
            return response()->json(['message' => 'انت لست استاذا لهذا الواجب'], 422);

        $group = Group::with('users')->find($groupItem->group_id);

        $subscriptions = [];

        $group->users()->get()->each(function ($user) use (&$subscriptions) {
            $subscriptions = array_merge($subscriptions, json_decode($user->device_subscriptions, true));
        });

        $subscriptions = array_unique($subscriptions);

        OneSignalNotifier::sendNotificationToUsers($subscriptions, $message);

        return response()->json([], 201);
    }

    public function getStudentAnswers(String $homework_id, String $group_id)
    {
        $homework = Homework::find($homework_id);
        $group = Group::find($group_id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        if (!$homework) {
            return response()->json(['message' => 'Homework not found'], 404);
        }

        $items = DB::table('users')
            ->join('group_user', 'group_user.user_id', '=', 'users.id')
            ->join('homework_groups', 'homework_groups.group_id', '=', 'group_user.group_id')
            ->leftJoin('homework_user_answers', 'homework_user_answers.user_id', '=', 'users.id')
            ->where('homework_groups.homework_id', $homework_id)
            ->where('group_user.group_id', $group_id)
            ->select('users.name', 'users.ref_number', 'homework_user_answers.attachments')
            ->get();

        $items->each(function ($item) {
            $item->attachments = json_decode($item->attachments ?? '[]');
        });

        return response()->json($items);
    }
}
