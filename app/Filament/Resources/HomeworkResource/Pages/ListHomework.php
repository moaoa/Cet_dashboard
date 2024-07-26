<?php

namespace App\Filament\Resources\HomeworkResource\Pages;

use App\Filament\Resources\HomeworkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomework extends ListRecords
{
    protected static string $resource = HomeworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(condition: auth()->user()->type === 1),//TODO: use an enum for types of the users
        ];
    }
}
