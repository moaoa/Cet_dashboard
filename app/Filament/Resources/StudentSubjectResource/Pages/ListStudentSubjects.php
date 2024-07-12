<?php

namespace App\Filament\Resources\StudentSubjectResource\Pages;

use App\Filament\Resources\StudentSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentSubjects extends ListRecords
{
    protected static string $resource = StudentSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
