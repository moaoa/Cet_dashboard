<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStatus;
use App\Filament\Resources\TeacherAbsenceResource\Pages;
use App\Filament\Resources\TeacherAbsenceResource\RelationManagers;
use App\Models\Group;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TeacherAbsence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class TeacherAbsenceResource extends Resource
{
    protected static ?string $model = TeacherAbsence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'غياب الاستاذ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required(),
                Forms\Components\Select::make('lecture_id')
                    ->relationship('lecture', 'id')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lecture.teacher.name')
                    ->numeric(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($state) => AttendanceStatus::from($state)->getLabel()),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lecture.subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lecture.group.name')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Forms\Components\Select::make('semester_id')
                            ->options(Semester::all()->pluck('name', 'id'))
                            ->live(),
                        Forms\Components\Select::make('subject_id')
                            ->options(
                                fn(Forms\Get $get) => Subject::where(
                                    'semester_id',
                                    $get('semester_id')
                                )->pluck('name', 'id')
                            )
                            ->live(),
                        Forms\Components\Select::make('group_id')
                            ->options(
                                fn(Forms\Get $get) => DB::table('group_subject')
                                    ->join('groups', 'groups.id', '=', 'group_subject.group_id')
                                    ->where('group_subject.subject_id', $get('subject_id'))
                                    ->pluck('groups.name', 'groups.id')
                            )
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->whereHas('lecture', function (Builder $query) use ($data) {
                                if ($data['subject_id'] == null) return $query;
                                $query->where('subject_id', $data['subject_id']);
                            })
                            ->whereHas('lecture', function (Builder $query) use ($data) {
                                if ($data['group_id'] == null) return $query;
                                $query->where('group_id', $data['group_id']);
                            });
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherAbsences::route('/'),
            'create' => Pages\CreateTeacherAbsence::route('/create'),
            'edit' => Pages\EditTeacherAbsence::route('/{record}/edit'),
        ];
    }
}
