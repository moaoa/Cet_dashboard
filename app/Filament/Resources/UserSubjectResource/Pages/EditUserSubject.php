<?php

namespace App\Filament\Resources\UserSubjectResource\Pages;

use App\Filament\Resources\UserSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserSubject extends EditRecord
{
    protected static string $resource = UserSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
