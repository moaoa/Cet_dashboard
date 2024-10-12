<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\Models\ClassRoom;
use App\Models\Lecture;
use DateTime;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditLecture extends EditRecord
{
    protected static string $resource = LectureResource::class;

    protected function getHeaderActions(): array
    {


        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
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
            $hoursToAdd = (int) $hoursToAdd;

            if (!strtotime($timeString)) {
                return new DateTime();
            }

            $time = new DateTime($timeString);

            if ($hoursToAdd > 0) {
                $time->modify("+$hoursToAdd hours");
            }
            //dd($timeString, $hoursToAdd);

            return $time;
        }


        $startTime = new DateTime($data['start_time']);

        $endTime = addHoursToTime($data['start_time'], $data['duration']);
        if ($endTime->format('H:i') > '18:00') {
            Notification::make()
                ->title('المدة المحاضرة تجاوز الحد الأقصى للوقت المسموح به')
                ->danger()
                ->send();
            return  $record;
        } else if (isTeacherAvailable($data['teacher_id'], $startTime, $endTime, $data['day_of_week'])) {
            Notification::make()
                ->title('الاستاذ يدرس محاضرة في نفس الوقت')
                ->danger()
                ->send();

            return  $record;
        } else {
            Notification::make()
                ->title('تم تعديل المحاضرة بنجاح')
                ->success()
                ->send();
            $record->fill($data)->save();
            return $record;
        }
    }
    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }
    public  function getSavedNotification(): ?Notification
    {
        return null;
    }
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('create'); // Redirect to the create page again
    // }
}
