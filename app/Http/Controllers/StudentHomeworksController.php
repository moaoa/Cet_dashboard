<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Homework;
use App\Models\Lecture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentHomeworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(String $subject_id): JsonResponse
    {
        $student = User::query()->where('name', 'ahmad')->first();
        // TODO: fix groups to group
        $group = $student->groups()->first();

        if(!$group){
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

        $data = $homeworks->map(function ($homework) use ($group, $group_users){
            $comments = $homework->comments->map(function ($comment) use ($group_users) {
                return [
                    'conent' => $comment->content,
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
                    ->first()?->due_time
            ];
        });

        return response()->json($data);
    }
}
