<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Teacher\HomeworkResource;
use Illuminate\Support\Facades\DB;
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

        // $items = DB::table('homework_groups')
        //     ->join('homework', 'homework.id', '=', 'homework_groups.homework_id')
        //     ->join('groups', 'groups.id', '=', 'homework_groups.group_id')
        //     ->where('homework.teacher_id', $teacher->id)
        //     ->select('homework.name', 'description', 'homework.attachments', 'groups.name as group_name')
        //     ->get();

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
            'group_id' => 'required|exists:groups,id',
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
        $homework->groups()->attach($validated['group_id'], ['due_time' => $request->input('due_time')]);

        // Return a success response
        return response()->json([
            'message' => 'Homework created and assigned to group successfully',
            'homework' => $homework,
        ], 201);
    }
    /**
     * Display the specified resource.
     *
     * @param Homework $homework
     * @return JsonResponse
     */
    public function show(Homework $homework): JsonResponse
    {
        return response()->json($homework);
    }
}
