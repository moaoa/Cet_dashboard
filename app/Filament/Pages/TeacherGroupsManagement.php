<?php

namespace App\Filament\Pages;

use App\Enums\Major;
use App\Models\Teacher;
use Filament\Pages\Page;

class TeacherGroupsManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationLabel = 'ادارة مواد ومجموعات الاستاذ';
    protected static ?string $navigationGroup = 'الاستاذ';
    public function getTitle(): string
    {
        return ('ادارة مواد ومجموعات الاستاذ');
    }

    // Customize the page heading
    public function getHeading(): string
    {
        return ('ادارة مواد ومجموعات الاستاذ');
    }

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
