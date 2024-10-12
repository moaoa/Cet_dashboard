<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\Models\Lecture;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;
use Mockery\Matcher\Not;

class CreateLecture extends CreateRecord
{
    protected static string $resource = LectureResource::class;

    protected function handleRecordCreation(array $data): Model
    {

        $record = Lecture::create([
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'day_of_week' => $data['day_of_week'],
            'subject_id' => $data['subject_id'],
            'class_room_id' => $data['class_room_id'],
            'group_id' => $data['group_id'],
            'teacher_id' => $data['teacher_id'],
        ]);
        Notification::make()
            ->title('تم إنشاء المحاضرة بنجاح')
            ->success()
            ->send();
        dd($record->toArray());

        return $record;
    }
    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create'); // Redirect to the create page again
    }
}
