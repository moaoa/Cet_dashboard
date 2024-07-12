<?php

namespace App\Filament\Resources\SemesterTimeResource\Pages;

use App\Filament\Resources\SemesterTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSemesterTime extends EditRecord
{
    protected static string $resource = SemesterTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
