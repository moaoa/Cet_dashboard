<?php

namespace App\Filament\Resources\HomeworkGroupResource\Pages;

use App\Filament\Resources\HomeworkGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeworkGroup extends EditRecord
{
    protected static string $resource = HomeworkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
