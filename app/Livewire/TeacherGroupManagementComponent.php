<?php

namespace App\Livewire;

use App\Enums\Major;
use App\Models\Group;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use Closure;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Tables;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeacherGroupManagementComponent extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.teacher-group-management-component');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Teacher::query())
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('ref_number')
                    ->sortable()
                    ->searchable()
                    ->label('رقم المعرف'),
            ])
            ->actions([
                Action::make('manage')
                    ->label('إدارة')
                    ->form([
                        Select::make('major')
                            ->options(Major::class)
                            ->default(Major::GENERAL)
                            ->live()
                            ->required(),
                        Select::make('semester_id')
                            ->options(fn(Forms\Get $get) => Semester::where('major', $get('major'))->pluck('name', 'id'))
                            ->live()
                            ->required(),
                        Select::make('group')
                            ->options(fn(Forms\Get $get) => Group::where('semester_id', $get('semester_id'))->pluck('name', 'id'))
                            ->live()
                            ->required(),
                        Select::make('subject')
                            ->options(fn(Forms\Get $get) => Subject::where('semester_id', $get('semester_id'))->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (Teacher $teacher, array $data) {
                        $teachersTeachingSubjectInCurrentGroup = DB::table('teacher_groups')
                            ->join('subject_teacher', 'subject_teacher.teacher_id', '=', 'teacher_groups.teacher_id')
                            ->join('group_subject', 'group_subject.subject_id', '=', 'subject_teacher.subject_id')
                            ->join('teachers', 'teachers.id', '=', 'subject_teacher.teacher_id')
                            ->join('groups', 'groups.id', '=', 'teacher_groups.group_id')
                            ->where('subject_teacher.subject_id', $data['subject'])
                            ->get();

                        $teacherAlreadyTeachingSubjectInCurrentGroup = $teachersTeachingSubjectInCurrentGroup
                            ->contains(function ($item) use ($teacher) {
                                return $item->id == $teacher->id;
                            });

                        if ($teacherAlreadyTeachingSubjectInCurrentGroup) {
                            Notification::make()
                                ->title('الاستاذ يدرس المادة بالفعل في المجموعة')
                                ->danger()
                                ->send();
                            return;
                        }

                        $otherTeacher = $teachersTeachingSubjectInCurrentGroup
                            ->where(function ($item) use ($teacher) {
                                return $item->id != $teacher->id;
                            })->first();

                        if ($otherTeacher) {
                            $subject_name = Subject::find($data['subject'])->name;
                            $group_name = Group::find($data['group'])->name;
                            $teacher_name = Teacher::find($otherTeacher->id)->name;

                            Notification::make()
                                ->title(
                                    'المادة ' . $subject_name . ' تدرس بالفعل في المجموعة ' . $group_name . ' من قبل الاستاذ ' . ' ' . $teacher_name
                                )
                                ->danger()
                                ->send();
                            return;
                        }

                        $teacher->groups()->syncWithoutDetaching($data['group']);
                        $teacher->subjects()->syncWithoutDetaching($data['subject']);
                        $subject = Subject::find($data['subject']);
                        $subject->groups()->syncWithoutDetaching($data['group']);

                        $teacher->save();
                        $subject->save();

                        Notification::make()
                            ->title('تم إضافة المجموعة بنجاح')
                            ->success()
                            ->send();
                    })
            ]);
    }
}
