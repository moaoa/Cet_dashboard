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
use App\Models\Group;
use App\Models\Subject;
use App\Services\OneSignalNotifier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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

        // Create the homework
        $homework = Homework::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'attachments' => json_encode($validated['attachments'] ?? []),  // Convert attachments to JSON if present
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

        $homeworks = Homework::where('subject_id', $subjectId)
            ->whereHas('groups', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            })
            ->with(['comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get();

        return response()->json([
            'homeworks' => $homeworks,
        ]);
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
                'user' => $answer->user,
                'attachments' => json_decode($answer->attachments),
                'created_at' => $answer->created_at,
            ];
        });

        return response()->json([
            'homework' => $homework,
        ]);
    }
}
