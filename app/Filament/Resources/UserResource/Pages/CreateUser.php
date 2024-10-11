<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function handleRecordCreation(array $data): User
    {
        $data['device_subscriptions'] = json_encode(['']);
        $ID = $data['ref_number'];
        $isIdExists = User::where('ref_number', $ID)->first();
        $isEmailExists = User::where('email', $data['email'])->first();
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
                ->title('تم اضافة الطالب بنجاح')
                ->success()
                ->send();

            $newUser = User::create($data);
            return $newUser;
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
