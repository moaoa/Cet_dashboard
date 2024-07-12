<?php

namespace App\Filament\Resources\QuizGroupResource\Pages;

use App\Filament\Resources\QuizGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuizGroup extends EditRecord
{
    protected static string $resource = QuizGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
