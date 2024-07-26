<?php

namespace App\Filament\Resources\UserSubjectResource\Pages;

use App\Filament\Resources\UserSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserSubjects extends ListRecords
{
    protected static string $resource = UserSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
