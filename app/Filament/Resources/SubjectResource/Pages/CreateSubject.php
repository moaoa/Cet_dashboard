<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use App\Models\Subject;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;

    protected function handleRecordCreation(array $data): Subject
    {
        $name = $data['name'];
        $semester_id = $data['semester_id'];

        $existingSubject = Subject::where('name', $name)->first();


        if ($existingSubject) {
            Notification::make()
                ->title('المادة بهذا الاسم والفصل موجودة بالفعل')
                ->danger()
                ->send();
            return $existingSubject;
        }

        // Subject does not exist, so proceed to create it
        $newSubject = Subject::create([
            'name' => $name,
            'semester_id' => $semester_id,
        ]);

        Notification::make()
            ->title('تم اضافة المادة بنجاح')
            ->success()
            ->send();

        return $newSubject;
    }
    protected function getCreatedNotification(): ?Notification
    {
        return null; // Disable the default notification
    }
}
