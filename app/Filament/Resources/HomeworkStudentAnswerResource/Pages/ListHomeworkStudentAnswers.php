<?php

namespace App\Filament\Resources\HomeworkStudentAnswerResource\Pages;

use App\Filament\Resources\HomeworkStudentAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomeworkStudentAnswers extends ListRecords
{
    protected static string $resource = HomeworkStudentAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
