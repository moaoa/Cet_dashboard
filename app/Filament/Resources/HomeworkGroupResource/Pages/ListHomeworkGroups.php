<?php

namespace App\Filament\Resources\HomeworkGroupResource\Pages;

use App\Filament\Resources\HomeworkGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomeworkGroups extends ListRecords
{
    protected static string $resource = HomeworkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
