<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\Teacher;
//use Blueprint\Contracts\Model;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;


class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;



    protected function   handleRecordUpdate(Model $record, array $data): Teacher
    {

        $ID = $data['ref_number'];
        $isIdExists = Teacher::where('ref_number', $ID)
            ->where('id', '!=', $record->id) // Ensure we don't check against the current record
            ->first();

        $isEmailExists = Teacher::where('email', $data['email'])
            ->where('id', '!=', $record->id) // Ensure we don't check against the current record
            ->first();
        //  dd($record);
        if ($isEmailExists) {
            Notification::make()
                ->title('البريد الإلكتروني موجود بالفعل')
                ->danger()
                ->send();
            return $isEmailExists;
        } else if ($isIdExists) {
            Notification::make()
                ->title('رقم الوظيفي موجود بالفعل')
                ->danger()
                ->send();
            return $isIdExists;
        } else {
            $record->fill($data)->save();

            Notification::make()
                ->title('تم تعديل الأستاذ بنجاح')
                ->success()
                ->send();
            return $record;
        }
    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }



    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }
    public  function getSavedNotification(): ?Notification
    {
        return null;
    }
}
