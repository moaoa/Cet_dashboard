<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use App\Models\Subject;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $name = $data['name'];
        $semester_id = $data['semester_id'];

        $existingSubject = Subject::where('name', $name)
            ->where('semester_id', $semester_id)
            ->where('id', '!=', $record->id) // Ensure we don't check against the current record
            ->first();

        if ($existingSubject) {
            Notification::make()
                ->title('المادة بهذا الاسم والفصل موجودة بالفعل')
                ->danger()
                ->send();

            return  Subject::where('name', 1);  // You can return null or any other response you see fit
        }

        // Subject does not exist, so proceed to update it
        $record->fill($data)->save();

        Notification::make()
            ->title('تم تعديل المادة بنجاح')
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
