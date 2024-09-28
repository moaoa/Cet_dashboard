<?php

namespace App\Filament\Resources\TeacherAbsenceResource\Pages;

use App\Filament\Resources\TeacherAbsenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherAbsence extends EditRecord
{
    protected static string $resource = TeacherAbsenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
