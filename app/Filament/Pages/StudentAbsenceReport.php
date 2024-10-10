<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;


class StudentAbsenceReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'تقرير عدد غياب الطلبة';

    protected static string $view = 'filament.pages.student-absence-report';

}
