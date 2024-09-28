<?php

namespace App\Filament\Resources\TeacherAbsenceResource\Pages;

use App\Filament\Resources\TeacherAbsenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeacherAbsences extends ListRecords
{
    protected static string $resource = TeacherAbsenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
