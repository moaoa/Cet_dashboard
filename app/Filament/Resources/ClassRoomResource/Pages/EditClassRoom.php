<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditClassRoom extends EditRecord
{
    protected static string $resource = ClassRoomResource::class;
    protected function handleRecordUpdate(Model $record, array $data): ClassRoom
    {
        //dd($record->id);
        $numberRoom = $data['Number_room'];
        $nameRoom = $data['room'];
        $combinedName = $nameRoom . ' ' . $numberRoom;
        //$existingClassRoom = ClassRoom::where('name', $combinedName)->first();
        $existingClassRoom = ClassRoom::where('name', $combinedName)->where('id', '!=', $record->id)->first();
        // dd($existingClassRoom->name);

        if (!$existingClassRoom) {
            $record->name = $combinedName;
            $record->save();
            Notification::make()
                ->title('تمت تعديل القاعة بنجاح')
                ->success()
                ->send();
            return $record;
        } else {
            Notification::make()
                ->title('الاسم  موجود بالفعل')
                ->danger()
                ->send();
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


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
