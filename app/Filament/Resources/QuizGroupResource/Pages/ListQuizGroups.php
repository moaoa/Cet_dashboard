<?php

namespace App\Filament\Resources\QuizGroupResource\Pages;

use App\Filament\Resources\QuizGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuizGroups extends ListRecords
{
    protected static string $resource = QuizGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
