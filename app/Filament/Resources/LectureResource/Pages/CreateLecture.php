<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\Models\Lecture;
use App\Models\User;
use DateTime;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;
use Mockery\Matcher\Not;

class CreateLecture extends CreateRecord
{
    protected static string $resource = LectureResource::class;



    protected function handleRecordCreation(array $data): Lecture
    {
        function isTeacherAvailable($teacherId, $startTime, $endTime, $dayOfWeek)
        {
            $conflictingLectures = Lecture::where('teacher_id', $teacherId)
                ->where('day_of_week', $dayOfWeek)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                            ->where('end_time', '>', $startTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '>=', $startTime)
                            ->where('end_time', '<=', $endTime);
                    });
                })
                ->exists();

            return $conflictingLectures;
        }

        function addHoursToTime($timeString, $hoursToAdd)
        {
            $time = new DateTime($timeString);

            $time->modify("+$hoursToAdd hours");

            return $time;
        }

        $startTime = new DateTime($data['start_time']);

        $endTime = addHoursToTime($data['start_time'], $data['duration']);
        if ($endTime->format('H:i') > '18:00') {
            Notification::make()
                ->title('المدة المحاضرة تجاوز الحد الأقصى للوقت المسموح به')
                ->danger()
                ->send();
            return  Lecture::where('id', 1)->first();
        }

        if (isTeacherAvailable($data['teacher_id'], $startTime, $endTime, $data['day_of_week'])) {
            Notification::make()
                ->title('الاستاذ يدرس محاضرة في نفس الوقت')
                ->danger()
                ->send();

            return  Lecture::where('id', 1)->first();
        }
        $newLec = Lecture::create([
            'start_time' => $startTime,
            'end_time' => $endTime,
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

        return $newLec;
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
