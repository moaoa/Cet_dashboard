<?php

namespace App\Filament\Resources\UserSubjectResource\Pages;

use App\Filament\Resources\UserSubjectResource;
use App\Models\Subject;
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
        $studentSubjectAttachments = [];

        foreach ($data['user_id'] as $key => $userId) {
            // Retrieve the semester of the current group
            $groupSemester = DB::table('groups')
                ->where('id', $data['group'])
                ->value('semester_id');

            // Check if the user has any group in the same semester
            $userHasGroupInSameSemester = User::where('id', $userId)
                ->whereHas('groups', function ($query) use ($groupSemester) {
                    $query->where('semester_id', $groupSemester);
                })
                ->exists();

            // If the user has a group in the same semester, show a notification but don't continue
            if ($userHasGroupInSameSemester) {
                Notification::make()
                    ->title('خطأ')
                    ->body(User::where('id', $userId)->first()->name . ' ' . 'مسجل في مجموعة بالفعل ')
                    ->danger()
                    ->send();
            } else {
                // If no record exists, add it to the $studentGroupAttachments array
                $studentGroupAttachments[] = [
                    'user_id' => $userId,
                    'group_id' => $data['group'],
                ];
            }

            // Check if the user already has a relation with the subject
            foreach ($data['subject_id'] as $subjectId) {
                $hasRelation = DB::table('user_subjects')
                    ->where('user_id', $userId)
                    ->where('subject_id', $subjectId)
                    ->exists();

                // If the relation does not exist, add to the $studentSubjectAttachments array
                if ($hasRelation) {
                    Notification::make()
                        ->title('خطأ')
                        ->body(User::where('id', $userId)->first()->name . ' ' .
                            'مسجل في المادة' . ' ' .
                            Subject::where('id', $subjectId)->first()->name . ' ' .
                            'بالفعل')
                        ->danger()
                        ->send();
                    continue;
                }
                $studentSubjectAttachments[] = [
                    'user_id' => $userId,
                    'subject_id' => $subjectId,
                ];
            }
        }

        // Insert group associations if there are any
        if (!empty($studentGroupAttachments)) {
            try {
                DB::table('group_user')->insert($studentGroupAttachments);
                Notification::make()
                    ->title('نجاح')
                    ->body('تمت اضافة مجموعات الطلبة بنجاح')
                    ->success()
                    ->send();
            } catch (\Throwable $th) {
                Notification::make()
                    ->title('خطأ')
                    ->body('حدث خطأ')
                    ->danger()
                    ->send();
            }
        }

        // Insert subject associations if there are any
        if (!empty($studentSubjectAttachments)) {
            try {
                DB::table('user_subjects')->insert($studentSubjectAttachments);
                Notification::make()
                    ->title('نجاح')
                    ->body('تمت اضافة مواد الطلبة بنجاح')
                    ->success()
                    ->send();
            } catch (\Throwable $th) {
                Notification::make()
                    ->title('خطأ')
                    ->body('حدث خطأ')
                    ->danger()
                    ->send();
            }
        }

        return UserSubject::where('user_id', $userId)->first();
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirect to the index page after creation
    }
}
