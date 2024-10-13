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
        if ($data['room'] == 'المكتبة' || $data['room'] == 'المسرح') {
            $numberRoom = '';
        } else {
            $numberRoom = $data['Number_room'];
        }
        $nameRoom = $data['room'];
        $trimmedString = str_replace(' ', '', $nameRoom);
        $combinedName = $trimmedString . ' ' . $numberRoom;
        $existingClassRoom = ClassRoom::where('name', $combinedName)->where('id', '!=', $record->id)->first();

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
