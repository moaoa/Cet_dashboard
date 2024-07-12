<?php

namespace App\Filament\Resources\SemesterTimeResource\Pages;

use App\Filament\Resources\SemesterTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSemesterTimes extends ListRecords
{
    protected static string $resource = SemesterTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
