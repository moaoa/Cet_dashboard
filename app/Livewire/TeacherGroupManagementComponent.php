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
                TextColumn::make('name'),
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
