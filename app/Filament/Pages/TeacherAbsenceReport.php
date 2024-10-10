<?php

// namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Pages\Actions\HeaderAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;


class TeacherAbsenceReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.teacher-absence-report';
}
