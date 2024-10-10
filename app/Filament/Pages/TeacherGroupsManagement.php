<?php

namespace App\Filament\Pages;

use App\Enums\Major;
use App\Models\Teacher;
use Filament\Pages\Page;

class TeacherGroupsManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.teacher-groups-management';

    public Teacher $selectedTeacher;

    public function getMajorOptions()
    {
        $options = [];

        collect(Major::cases())
            ->each(function ($major) use (&$options) {
                $options[$major->value] = $major->getLabel();
            });

        return $options;
    }

    public function selectTeacher($teacher_id)
    {
        $this->selectedTeacher = Teacher::find($teacher_id);
    }
    public function getTeachers()
    {
        return Teacher::all();
    }
}
