<?php

namespace App\Filament\Resources\SubjectTeacherResource\Pages;

use App\Filament\Resources\SubjectTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubjectTeachers extends ListRecords
{
    protected static string $resource = SubjectTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
