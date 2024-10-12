<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\Teacher;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;


    protected function handleRecordCreation(array $data): Teacher

    {


        $data['device_subscriptions'] = json_encode(['']);
        $ID = $data['ref_number'];
        $isIdExists = Teacher::where('ref_number', $ID)->first();
        $isEmailExists = Teacher::where('email', $data['email'])->first();
        if ($isEmailExists) {
            Notification::make()
                ->title('البريد الإلكتروني موجود بالفعل')
                ->danger()
                ->send();
            return $isEmailExists;
        }
        if ($isIdExists) {
            Notification::make()
                ->title('الاسم  موجود بالفعل')
                ->danger()
                ->send();
            return $isIdExists;
        } else {
            Notification::make()
                ->title('تم اضافة الأستاذ بنجاح')
                ->success()
                ->send();
            $newTeacher = Teacher::create($data);
            return $newTeacher;
        }
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
