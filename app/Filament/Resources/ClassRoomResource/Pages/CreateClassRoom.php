<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Class_;

class CreateClassRoom extends CreateRecord
{
    protected static string $resource = ClassRoomResource::class;
    protected  function handleRecordCreation(array $data): ClassRoom
    {
        $numberRoom = $data['Number_room'];
        $nameRoom = $data['room'];
        $combinedName = $nameRoom . ' ' . $numberRoom;
        $existingClassRoom = ClassRoom::where('name', $combinedName)->first();
        if (!$existingClassRoom) {
            $newClassRoom = ClassRoom::create([
                'name' => $combinedName
            ]);
            Notification::make()
                ->title('تمت أضافة القاعة بنجاح')
                ->success()
                ->send();
            return $newClassRoom;
        } else {
            Notification::make()
                ->title('الاسم  موجود بالفعل')
                ->danger()
                ->send();
            return $existingClassRoom;
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
