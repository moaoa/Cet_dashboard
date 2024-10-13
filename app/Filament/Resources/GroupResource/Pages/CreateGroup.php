<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Models\Group;
use Blueprint\Contracts\Model;
use Blueprint\Models\Model as ModelsModel;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateGroup extends CreateRecord
{
    protected static string $resource = GroupResource::class;

    protected function handleRecordCreation(array $data): Group
    {
        $name = $data['name'];
        $major = $data['major'];
        $semester_id = $data['semester_id'];

        $existingGroup = Group::where('name', $name)
            ->where('semester_id', $semester_id)
            ->first();

        if ($existingGroup) {
            Notification::make()
                ->title('المجموعة بهذا الاسم والفصل موجودة بالفعل')
                ->danger()
                ->send();

            return  $existingGroup;  // You can return null or any other response you see fit
        }

        // Group does not exist, so proceed to create it
        $newGroup = Group::create([
            'name' => $name,
            'major' => $major,
            'semester_id' => $semester_id,
        ]);

        Notification::make()
            ->title('تم إنشاء المجموعة بنجاح')
            ->success()
            ->send();

        return $newGroup;
    }



    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index'); // Redirect to the index page after creation
    // }
}
