<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function handleRecordUpdate(Model $record, array $data): User
    {
        $ID = $data['ref_number'];
        $isIdExists = User::where('ref_number', $ID)->first();
        $isEmailExists = User::where('email', $data['email'])->first();
        if ($isEmailExists) {
            Notification::make()
                ->title('البريد الإلكتروني موجود بالفعل')
                ->danger()
                ->send();
            return $isEmailExists;
        } else if ($isIdExists) {
            Notification::make()
                ->title('رقم القيد موجود بالفعل')
                ->danger()
                ->send();
            return $isIdExists;
        } else {
            Notification::make()
                ->title('تم تعديل الطالب بنجاح')
                ->success()
                ->send();
            $record->update($data);
            $record->save();
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
}
