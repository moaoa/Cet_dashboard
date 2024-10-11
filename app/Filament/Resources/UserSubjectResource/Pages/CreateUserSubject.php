<?php

namespace App\Filament\Resources\UserSubjectResource\Pages;

use App\Filament\Resources\UserSubjectResource;
use App\Models\User;
use App\Models\UserSubject;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateUserSubject extends CreateRecord
{
    protected static string $resource = UserSubjectResource::class;
    protected function handleRecordCreation(array $data): UserSubject
    {

        $studentGroupAttachments = [];

        foreach ($data['user_id'] as $key => $userId) {
            // Retrieve the semester of the current group
            $groupSemester = DB::table('groups')
                ->where('id', $data['group'])
                ->value('semester_id'); // Get the semester of the group from $data

            // Check if the user has any group in the same semester
            $userHasGroupInSameSemester = User::where('id', $userId)
                ->whereHas('groups', function ($query) use ($groupSemester) {
                    // Ensure the group belongs to the same semester
                    $query->where('semester_id', $groupSemester);
                })
                ->exists();

            // If the user has a group in the same semester, perform logic (e.g., skip, log, etc.)
            if ($userHasGroupInSameSemester) {
                Notification::make()
                    ->title('خطأ')
                    ->body(User::where('id', $userId)->first()->name . ' ' . 'مسجل في مجموعة بالفعل ')
                    ->danger()
                    ->send();
                continue;
            }

            // If no record exists, add it to the $studentGroupAttachments array
            $studentGroupAttachments[] = [
                'user_id' => $userId,
                'group_id' => $data['group'],
            ];
        }
        DB::table('group_user')->insert($studentGroupAttachments);
        // $student=$studentGroupAttachments[0];
        // Notification::make()
        //     ->title('Error')
        //     ->body($student['user_id'])
        //     ->success()
        //     ->send();
        // dd($studentGroupAttachments);
        return UserSubject::where('user_id', $userId)->first();
    }
    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }
}
