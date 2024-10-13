<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Models\Group;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGroup extends EditRecord
{
    protected static string $resource = GroupResource::class;


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $name = $data['name'];
        $major = $data['major'];
        $semester_id = $data['semester_id'];

        $existingGroup = Group::where('name', $name)
            ->where('semester_id', $semester_id)
            ->where('id', '!=', $record->id) // Ensure we don't check against the current record
            ->first();

        if ($existingGroup) {
            Notification::make()
                ->title('المجموعة بهذا الاسم والفصل موجودة بالفعل')
                ->danger()
                ->send();

            return  Group::where('name', 1);  // You can return null or any other response you see fit
        }

        // Group does not exist, so proceed to update it
        $record->fill($data)->save();

        Notification::make()
            ->title('تم تعديل المجموعة بنجاح')
            ->success()
            ->send();

        return $record;
    }
    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }

    public function getSavedNotification(): ?Notification
    {
        return null; // Disable the saved notification
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
