<?php

namespace App\Filament\Resources\SubjectTeacherResource\Pages;

use App\Filament\Resources\SubjectTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubjectTeacher extends EditRecord
{
    protected static string $resource = SubjectTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
