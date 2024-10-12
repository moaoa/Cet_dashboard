<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;


class StudentAbsenceReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'تقرير عدد غياب الطلبة';
    protected static ?string $navigationGroup = 'الطالب';
    protected static ?int $navigationSort = 5;

    public function getTitle(): string
    {
        return 'تقرير غياب الطلبة'; // Directly write the title in Arabic
    }
    protected static string $view = 'filament.pages.student-absence-report';
}
